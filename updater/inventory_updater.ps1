<#
.SYNOPSIS
    Inventory Updater Powershell Script
.DESCRIPTION
    This is a drop-in replacement for the java based inventory updater program
.PARAMETER Url
    Full path to the inventory website to be used for web calls, example http://localhost/inventory
.PARAMETER ApiAuthKey
    The authentication key for the inventory API endpoint. This is set in the inventory manager. All calls will fail if it is not matching.
.PARAMETER CheckPrograms
    True/False value that defines if the programs on the computer should be sent. Defaults to True
.PARAMETER CheckServices
    True/False value that defines if the services on the computer should be sent. Defaults to True
.PARAMETER CheckChoco
    True/False value that defines if the chocolatey applications outdated check should be performed. Defaults to False
.EXAMPLE
    C:\PS>inventory_updater.ps1 -Url http://localhost/inventory -ApiAuthKey key -CheckPrograms False
.NOTES
    Author: Rob Weber   
#>
param(
[Parameter(Mandatory=$true,Position=0)][ValidateNotNullOrEmpty()][string]$Url, 
[Parameter(Mandatory=$true,Position=1)][ValidateNotNullOrEmpty()][string]$ApiAuthKey,
[Parameter(Mandatory=$false,Position=2)][ValidateSet("true","false")][string]$CheckPrograms = "True",
[Parameter(Mandatory=$false,Position=3)][ValidateSet("true","false")][string]$CheckServices = "True",
[Parameter(Mandatory=$false,Position=4)][ValidateSet("true","false")][string]$CheckChoco = "False",
[Parameter(Mandatory=$false,Position=5)][boolean]$DebugLog = $False
)

#lowest powershell version this script will support
$minPowerShellVersion = 5

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
		$output = Invoke-WebRequest -Method 'Post' -Uri ($apiUrl + $endpoint) -Body $jsonData -ContentType "application/json" -Headers @{"x-auth-key"="$ApiAuthKey"} -UseBasicParsing | ConvertFrom-Json
	}
	catch{
		Continue
	}
	
	#Write-Host $output
	return $output 
}

#log to website log file
function web-log{
	param([string]$logger = "Updater", 
	[string]$level = "INFO", 
	[string]$message)
	
	#use current data/time
	$now = (Get-Date -Format "yyyy-MM-dd HH:mm:ss")
	
	#send the log message
	if($DebugLog){
		Write-Host $message
	}
	
	$output = web-call -Endpoint "/add_log" -Data @{date = "$now"; logger = "$logger"; level = "$level"; message = "$message"}
    return $output
}

#startup the script
$logSuccess = web-log -Message "Starting inventory collection"

if($logSuccess.type -eq 'error'){
    Write-Host $logSuccess.message
    exit 2
}

#get the settings
$settingsObj = web-call -Endpoint "/settings" -Data @{}
$settings = $settingsObj."result"

###PART 1 - collect PC info

$computerInfo = @{} #hashtable to store information

#COMPUTER NAME
$win32Output = (Get-CimInstance Win32_OperatingSystem)
$ComputerName = $win32Output.csname
$computerInfo.ComputerName = $ComputerName

#check the powershell version
if($PSVersionTable.PSVersion.major -lt $minPowerShellVersion){
	web-log -Message "$ComputerName powershell version not compatible" -Level "ERROR" | out-null
	exit 2
}

web-log -Message "Gathering PC Information" | out-null

#MEMORY
$computerInfo.Memory = [math]::round($win32Output.totalvisiblememorysize / 1024/1024, 3)
$computerInfo.MemoryFree = [math]::round($win32Output.freephysicalmemory / 1024/1024, 3)

#PROCESSOR
$win32_processor = (Get-WmiObject win32_processor)

if($win32_processor -ne $null -And $win32_processor.count -gt 1){
	$computerInfo.CPU = $win32_processor[0].name
}
else
{
	$computerInfo.CPU = $win32_processor.name
}

#NETWORK
#gets network info where the adapter is enabled and has an IP, use the first one
$win32_network = @(Get-WmiObject win32_networkadapterconfiguration | Select-Object -Property @{name='IPAddress';Expression={($_.IPAddress[0])}},@{name="IPV6";Expression={($_.IPAddress[1])}},MacAddress | Where IPAddress -NE $null)

if($win32_network -ne $null -And $win32_network.count -gt 0){
	#returned array of addresses
	$computerInfo.IPaddress = $win32_network[0].IPAddress
    $computerInfo.IPv6address = $win32_network[0].IPV6
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
#check if class is implemented
Get-CimInstance -Namespace root\wmi -ClassName WmiMonitorBasicDisplayParams -ErrorAction SilentlyContinue 2> $NULL > $NULL

if($?){
	$computerInfo.NumberOfMonitors = (Get-CimInstance -Namespace root\wmi -ClassName WmiMonitorBasicDisplayParams | where {$_.Active -like "True"}).Active.Count
}
else{
	#wmi class not implemented, assume 1
	$computerInfo.NumberOfMonitors = 1
}

#LAST BOOT TIME
$computerInfo.LastBootTime = $win32Output.LastBootUpTime | Get-Date -Format "yyyy-MM-dd HH:mm:ss"

#CHOCO - not everyone will want this
if(evalBool $CheckChoco -And (Get-Command "choco" -errorAction SilentlyContinue)){
	
	$computerInfo.ApplicationUpdates = @((choco outdated --limit-output)).count
	
}
else{
	$computerInfo.ApplicationUpdates = 0
}

#DATE/TIME OF UPDATE
$computerInfo.LastUpdated = (Get-Date -Format "yyyy-MM-dd HH:mm:ss")

#print out the computer update string if on debug
if($DebugLog){
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
	web-log -Message "Updating Computer $ComputerName" | out-null
	$ComputerId = $output."result".id
	$computerInfo.id = $output."result".id
	
	$updateOutput = web-call -Endpoint "/inventory/update" -Data $computerInfo
	
	if($updateOutput."type" -eq "success")
	{
		web-log -Message "$ComputerName has been updated" | out-null
	}
	else
	{
		web-log -Message "Error Updating $ComputerName" -level "ERROR" | out-null
	}
}
else
{
	web-log -Message "$ComputerName not in inventory system" -level "WARNING" | out-null
	
	if($settings.computer_ignore_list)
	{
		#check if this computer in the ignore list
		if($settings.computer_ignore_list.ToLower().Contains($ComputerName.ToLower()))
		{
			#don't send the data, end here
			web-log -Message "$ComputerName on the ignore list, not sending add request" -level "ERROR" | out-null
			exit 0 
		}
	}
	
	if($settings.computer_auto_add.ToLower() -eq "true")
	{
		#make sure there is a default location
		$defaultObj = web-call -Endpoint "/location/default" -Data @{}
		
		if($defaultObj."type" -ne "success")
		{
			web-log -Message "Need default location to auto-add" -level "ERROR" | out-null
			exit 0
		}
		
		#add the computer
		$addOutput = web-call -Endpoint "/inventory/add" -Data @{ComputerName = "$ComputerName" }
	
		if($addOutput."type" -eq "success")
		{
			#save the id for later
			$ComputerId = $addOutput."result".id
			$computerInfo.id = $ComputerId
			
			web-log -Message "Added $Computername with id: $ComputerId" | out-null
			
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
			web-log -Message "Error auto-adding $ComputerName" -level "ERROR" | out-null
		}
	}
	else
	{
		#send an add request
		web-log -Message "Sending add request for $ComputerName" | out-null
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
$disks = @($(Get-WmiObject -Class Win32_LogicalDisk -Filter "DriveType=3" | Select DeviceId, FreeSpace, Size))

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
	
	web-log -Message "Found $($allPrograms.count) programs on $ComputerName" | out-null
	
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
	
	web-log -Message "Found $($allServices.count) services on $ComputerName" | out-null
	
	#clear out the current services list
	$clearOutput = web-call -Endpoint "/services/clear" -Data @{id = $ComputerId}
	
	foreach ($service in $allServices.GetEnumerator()){
		$serviceOutput = web-call -Endpoint "/services/add" -Data @{id = $ComputerId; name = $service.DisplayName; mode = $service.StartMode; status = $service.State}
	}
}