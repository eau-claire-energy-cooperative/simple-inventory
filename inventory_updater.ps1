#################################################################
#inventory_updater.ps1 
#Author: Rob Weber
#
# This is a drop-in replacement for the java based inventory updater program
#
#
##################################################################

param([string]$Url, 
[boolean]$debug = $False,
[ValidateSet("true","false")][string]$CheckChoco = "False",
[ValidateSet("true","false")][string]$CheckPrograms = "True",
[ValidateSet("true","false")][string]$CheckServices = "True")

#BaseURL
$apiUrl = $Url + "/api"

#for SSL
[Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12

#helper function for string to boolean values
function evalBool(){
	param([string]$boolValue)
	
	switch($boolValue.ToLower()){
		"true" {
			return $True
		}
		default {
			return $False
		}
	}
}

#call the web api with the following json data
function web-call{
	param([string]$endpoint, [hashtable] $data)
	
	#convert to json string
	$jsonData = $data | ConvertTo-Json -Compress
	$output = @{}
	try{
		$output = Invoke-WebRequest -Method 'Post' -Uri ($apiUrl + $endpoint) -Body $jsonData -ContentType "application/json" -UseBasicParsing | ConvertFrom-Json
	}
	catch{
		Continue
	}
	
	#Write-Host $output
	return $output 
}

#log to website log file
function web-log{
	param([string]$logger = "Main", 
	[string]$level = "INFO", 
	[string]$message)
	
	#use current data/time
	$now = (Get-Date -Format "yyyy-MM-dd HH:mm:ss")
	
	#send the log message
	if($debug){
		Write-Host $message
	}
	
	$output = web-call -Endpoint "/add_log" -Data @{date = "$now"; logger = "$logger"; level = "$level"; message = "$message"}
}

#startup the script
web-log -Message "Starting inventory collection"

#get the settings
$settingsObj = web-call -Endpoint "/settings" -Data @{}
$settings = $settingsObj."result"

###PART 1 - collect PC info

$computerInfo = @{} #hashtable to store information

#COMPUTER NAME
$win32Output = (Get-CimInstance Win32_OperatingSystem)
$ComputerName = $win32Output.csname
#$ComputerName = "Thor2"
$computerInfo.ComputerName = $ComputerName

web-log -Message "Gathering PC Information"

#MEMORY
$computerInfo.Memory = [math]::round($win32Output.totalvisiblememorysize / 1024/1024, 3)
$computerInfo.MemoryFree = [math]::round($win32Output.freephysicalmemory / 1024/1024, 3)

#PROCESSOR
$win32_processor = (Get-WmiObject win32_processor)
$computerInfo.CPU = $win32_processor.name

#NETWORK
#gets network info where the adapter is enabled and has an IP, use the first one
$win32_network = Get-WmiObject win32_networkadapterconfiguration | Select-Object -Property @{name='IPAddress';Expression={($_.IPAddress[0])}},MacAddress | Where IPAddress -NE $null

if($win32_network.count -gt 0){
	$computerInfo.IPaddress = $win32_network[0].IPAddress
	$computerInfo.MACaddress = $win32_network[0].MacAddress
}
else{
	$computerInfo.IPaddress = ""
	$computerInfo.MACaddress = ""
}

#CURRENT USER
$win32_user = $(Get-WMIObject -class Win32_ComputerSystem | select username).username

#strip domain
if($win32_user.contains("\"))
{
	$win32_user = $win32_user.split("\")[1]
}

if($ComputerName.ToUpper() + '$' -eq $win32_user)
{
	#no user or using a system account
	$win32_user = "Local System Account"
}

$computerInfo.CurrentUser = $win32_user

#OPERATING SYSTEM 
$computerInfo.OS = $win32Output.Caption
$computerInfo.OS_Arch = $win32Output.OSArchitecture

#MODEL INFORMATION
$win32_bios = $(Get-WMIObject -class Win32_BIOS | select SerialNumber, Manufacturer)
$win32_computersystem = $(Get-WmiObject -Class Win32_ComputerSystem | select Model)

$computerInfo.SerialNumber = $win32_bios.SerialNumber
$computerInfo.Manufacturer = $win32_bios.Manufacturer
$computerInfo.Model = $win32_computersystem.Model

#MONITORS
#gets number of active monitors
$computerInfo.NumberOfMonitors = (Get-CimInstance -Namespace root\wmi -ClassName WmiMonitorBasicDisplayParams | where {$_.Active -like "True"}).Active.Count

#LAST BOOT TIME
$computerInfo.LastBootTime = $win32Output.LastBootUpTime | Get-Date -Format "yyyy-MM-dd HH:mm:ss"

#CHOCO - not everyone will want this
if(evalBool $CheckChoco){
	$computerInfo.ApplicationUpdates = (choco outdated --limit-output).count
}
else{
	$computerInfo.ApplicationUpdates = 0
}

#DATE/TIME OF UPDATE
$computerInfo.LastUpdated = (Get-Date -Format "yyyy-MM-dd HH:mm:ss")

#print out the computer update string if on debug
if($debug){
	Write-Host ""
	$computerInfo | ConvertTo-Json -Compress
	Write-Host ""
}


#SEND RESULTS
$ComputerId = $null
$output = web-call -Endpoint "/inventory/exists" -Data @{computer = "$ComputerName"} 

if($output."type" -eq "success")
{
	#get the computer id and update
	web-log -Message "Updating Computer $ComputerName"
	$ComputerId = $output."result".id
	$computerInfo.id = $output."result".id
	
	$updateOutput = web-call -Endpoint "/inventory/update" -Data $computerInfo
	
	if($updateOutput."type" -eq "success")
	{
		web-log -Message "$ComputerName has been updated"
	}
	else
	{
		web-log -Message "Error Updating $ComputerName" -level "ERROR"
	}
}
else
{
	web-log -Message "$ComputerName not in inventory system" -level "WARNING"
	
	if($settings.computer_ignore_list)
	{
		#check if this computer in the ignore list
		if($settings.computer_ignore_list.ToLower().Contains($ComputerName.ToLower()))
		{
			#don't send the data, end here
			web-log -Message "$ComputerName on the ignore list, not sending add request" -level "ERROR"
			exit 0 
		}
	}
	
	if($settings.computer_auto_add.ToLower() -eq "true")
	{
		#make sure there is a default location
		$defaultObj = web-call -Endpoint "/location/default" -Data @{}
		
		if($defaultObj."type" -ne "success")
		{
			web-log -Message "Need default location to auto-add" -level "ERROR"
			exit 0
		}
		
		#add the computer
		$addOutput = web-call -Endpoint "/inventory/add" -Data @{ComputerName = "$ComputerName" }
	
		if($addOutput."type" -eq "success")
		{
			#save the id for later
			$ComputerId = $addOutput."result".id
			$computerInfo.id = $ComputerId
			
			web-log -Message "Added $Computername with id: $ComputerId"
			
			#try and send the computer info again
			$updateOutput = web-call -Endpoint "/inventory/update" -Data $computerInfo
			
			#notify admin via email
			$compUrl = $Url + "/inventory/moreInfo/$ComputerId"
			$message = "Computer <b>$ComputerName</b> has been added to the inventory. Details are below: <br><br>" + 
			"Model: $($computerInfo.Model)<br>" + 
			"Serial Number: $($computerInfo.SerialNumber)<br>" + 
			"Current User: $($computerInfo.CurrentUser)<br>" + 
			'<a href="' + $compUrl + '">' + $compUrl + '</a>'
			
			$email = web-call -Endpoint "/send_email" -Data @{subject = "Computer Added"; message = $message }
		}
		else
		{
			web-log -Message "Error auto-adding $ComputerName" -level "ERROR"
		}
	}
	else
	{
		#send an add request
		web-log -Message "Sending add request for $ComputerName"
		$message = "Computer <b>$ComputerName</b> is requesting to be added to the inventory. Details are below: <br><br>" + 
			"Model: $($computerInfo.Model)<br>" + 
			"Serial Number: $($computerInfo.SerialNumber)<br>" + 
			"Current User: $($computerInfo.CurrentUser)"
		
		$email = web-call -Endpoint "/send_email" -Data @{subject = "Computer Add Request"; message = $message }
		
		#can't go any farther
		exit 0
	}
}

###PART 2 - find drives, programs, and services

if($ComputerId -eq $null)
{
	#can't do any of this stuff
	exit 0
}

#DISKS

#only get local drives
$disks = $(Get-WmiObject -Class Win32_LogicalDisk -Filter "DriveType=3" | Select DeviceId, FreeSpace, Size)

foreach ($disk in $disks.GetEnumerator()){
	$diskOutput = web-call -Endpoint "/disk/update" -Data @{comp_id = $ComputerId; type = "Local"; label=$disk.DeviceId; total_space = $disk.Size/1025; space_free = $disk.FreeSpace/1024}
}

#PROGRAMS 
if(evalBool($CheckPrograms))
{
	#get all 32 and 64 bit applications
	$allProgram = @()
	$allPrograms += $(Get-ItemProperty HKLM:\Software\Microsoft\Windows\CurrentVersion\Uninstall\* | Select-Object DisplayName, DisplayVersion)
	$allPrograms += $(Get-ItemProperty HKLM:\Software\Wow6432Node\Microsoft\Windows\CurrentVersion\Uninstall\* | Select-Object DisplayName, DisplayVersion)
	
	web-log -Message "Found $($allPrograms.count) programs on $ComputerName" 
	
	#clear out the current programs list
	$clearOutput = web-call -Endpoint "/programs/clear" -Data @{id = $ComputerId}

	foreach ($program in $allPrograms.GetEnumerator()){
		if($program.DisplayVersion -eq $null -Or $program.DisplayVersion -eq "")
		{
			$program.DisplayVersion = "?"
		}
		
		$programOutput = web-call -Endpoint "/programs/add" -Data @{id = $ComputerId; program = $program.DisplayName; version = $program.DisplayVersion}
	}
}

#SERVICES
if(evalBool($CheckServices))
{
	$allServices = $(Get-WmiObject -Class Win32_Service | Select DisplayName, StartMode, State)
	
	web-log -Message "Found $($allServices.count) services on $ComputerName"
	
	#clear out the current services list
	$clearOutput = web-call -Endpoint "/services/clear" -Data @{id = $ComputerId}
	
	foreach ($service in $allServices.GetEnumerator()){
		$serviceOutput = web-call -Endpoint "/services/add" -Data @{id = $ComputerId; name = $service.DisplayName; mode = $service.StartMode; status = $service.State}
	}
}