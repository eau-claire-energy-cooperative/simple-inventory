@echo off

@echo .
@echo . Installs Simple Inventory Service
@echo .


setlocal
@rem note that if JVM not found, service 'does not report an error' when startup fails, although event logged

set JVMDIR=C:\Program Files (x86)\Java\jre7\bin\client
set JSBINDIR=C:\Inventory\scripts\
set JSEXE=%JSBINDIR%\InventoryService.exe
set SSBINDIR=C:\Inventory


@echo Inventory... Press Control-C to abort
@pause
@echo on
"%JSEXE%" -install "Simple Inventory Service" "%JVMDIR%\jvm.dll" -Djava.class.path="%SSBINDIR%\inventory.jar" -start com.ecec.rweber.inventory.Main -stop com.ecec.rweber.inventory.Main -method stopService -out "%JSBINDIR%\stdout.log" -err "%JSBINDIR%\stderr.log" -current "%SSBINDIR%" -manual -description "Simple Inventory Service"
@echo .

@pause