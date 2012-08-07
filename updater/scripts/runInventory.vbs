Set ofso = CreateObject("Scripting.FileSystemObject") 
Dim javaPath 
If ofso.FileExists("C:\windows\system32\java.exe") Then 
javaPath = "C:\windows\system32\java.exe" 
ElseIf ofso.FileExists("C:\windows\syswow64\java.exe") Then 
javaPath = "C:\windows\syswow64\java.exe" 
Else 
MsgBox("You do not appear to have Java Web Start, please install Java and run this script again") 
WScript.Quit 
End If 
Dim WshShell 
Set WshShell = CreateObject("WScript.Shell") 
WshShell.CurrentDirectory = "\\10.10.10.3\IT\InventoryUpdater\" 
cmds=WshShell.Run(javaPath & " -jar inventory.jar -schedule=false",0,True) 
