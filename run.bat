@echo off
echo ========================================
echo Instagram Clone - Python Flask Server
echo ========================================
echo.
echo Checking Python installation...
python --version
echo.
echo Installing dependencies...
pip install -r requirements.txt
echo.
echo Starting server...
echo.
python app.py
pause
