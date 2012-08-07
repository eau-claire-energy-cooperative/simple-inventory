Set ofso = CreateObject("Scripting.FileSystemObject")
Dim javaPath
If ofso.FileExists("%WINDIR%\system32\javaws.exe") Then
	javaPath = "%WINDIR%\system32\javaws.exe"
ElseIf ofso.FileExists("%WINDIR%\syswow64\javaws.exe") Then
	javaPath = "%WINDIR%\syswow64\javaws.exe"
Else
	MsgBox("You do not appear to have Java Web Start, please install Java and run this script again")
	WScript.Quit
End If

Dim WshShell
Set WshShell = CreateObject("WScript.Shell")
WshShell.CurrentDirectory = oFSO.GetParentFolderName(Wscript.ScriptFullName)
cmds=WshShell.Run(javaPath & " -jar inventory.jar -schedule=false",0,True)



