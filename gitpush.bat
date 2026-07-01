@echo off
set message=%*

if "%message%"=="" (
    echo Geef een commit message mee!
    exit /b
)

git add .
git commit -m "%message%"
git push

