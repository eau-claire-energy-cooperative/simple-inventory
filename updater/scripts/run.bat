@echo off
cd "%USERPROFILE%"
del runInventory.vbs

echo Set ofso = CreateObject("Scripting.FileSystemObject") >> runInventory.vbs
echo Dim javaPath >> runInventory.vbs
echo If ofso.FileExists("%WINDIR%\system32\java.exe") Then >> runInventory.vbs
echo javaPath = "%WINDIR%\system32\java.exe" >> runInventory.vbs
echo ElseIf ofso.FileExists("%WINDIR%\syswow64\java.exe") Then >> runInventory.vbs
echo javaPath = "%WINDIR%\syswow64\java.exe" >> runInventory.vbs
echo Else >> runInventory.vbs
echo MsgBox("You do not appear to have Java Web Start, please install Java and run this script again") >> runInventory.vbs
echo WScript.Quit >> runInventory.vbs
echo End If >> runInventory.vbs

echo Dim WshShell >> runInventory.vbs
echo Set WshShell = CreateObject("WScript.Shell") >> runInventory.vbs
echo WshShell.CurrentDirectory = "\\10.10.10.35\inventory\updater\" >> runInventory.vbs
echo cmds=WshShell.Run(javaPath ^& " -jar inventory.jar -schedule=false",0,True) >> runInventory.vbs

runInventory.vbs
del runInventory.vbs
