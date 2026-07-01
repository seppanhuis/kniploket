@echo off
setlocal enabledelayedexpansion

:: Commit message ophalen
set message=%*

if "%message%"=="" (
    echo Geef een commit message mee!
    exit /b
)

:: Git commands
git add .
git commit -m "%message%"

echo.
echo Beschikbare branches:
echo.

set count=0

:: Branches ophalen
for /f "delims=" %%b in ('git branch') do (
    set line=%%b

    :: Sterretje verwijderen bij huidige branch
    set line=!line:* =!

    set /a count+=1
    set branch[!count!]=!line!
    echo !count!. !line!
)

echo.
set /p choice=Kies een branch nummer:

set selected=!branch[%choice%]!

if "!selected!"=="" (
    echo Ongeldige keuze!
    exit /b
)

echo.
echo Push naar !selected!...
git push origin !selected!

endlocal
