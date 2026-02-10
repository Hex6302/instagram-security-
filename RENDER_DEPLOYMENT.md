# Deploy to Render - Step by Step Guide

## Prerequisites
- GitHub account
- Render account (free tier available at https://render.com)
- Your code pushed to a GitHub repository

## Step 1: Push Code to GitHub

1. **Initialize Git** (if not already done):
   ```bash
   cd c:\Users\Dinesh.S\Desktop\instagram
   git init
   git add .
   git commit -m "Initial commit - Instagram clone"
   ```

2. **Create GitHub Repository**:
   - Go to https://github.com/new
   - Name it: `instagram-clone`
   - Don't initialize with README (you already have files)
   - Click "Create repository"

3. **Push to GitHub**:
   ```bash
   git remote add origin https://github.com/YOUR_USERNAME/instagram-clone.git
   git branch -M main
   git push -u origin main
   ```

## Step 2: Deploy on Render

1. **Sign up/Login to Render**:
   - Go to https://render.com
   - Sign up with GitHub (recommended)

2. **Create New Web Service**:
   - Click "New +" button
   - Select "Web Service"
   - Connect your GitHub repository: `instagram-clone`

3. **Configure Service**:
   - **Name**: `instagram-clone` (or any name you prefer)
   - **Region**: Choose closest to your users
   - **Branch**: `main`
   - **Root Directory**: Leave empty
   - **Environment**: `Python 3`
   - **Build Command**: `pip install -r requirements.txt`
   - **Start Command**: `gunicorn app:app`

4. **Environment Variables** (Important!):
   Click "Advanced" and add:
   - `PYTHON_VERSION` = `3.12.6`

5. **Choose Plan**:
   - Select **Free** tier (perfect for testing)
   - Note: Free tier sleeps after 15 minutes of inactivity

6. **Deploy**:
   - Click "Create Web Service"
   - Wait 2-5 minutes for deployment
   - Render will show build logs

## Step 3: Access Your Site

Once deployed, Render gives you a URL like:
```
https://instagram-clone-xxxx.onrender.com
```

Your login page will be at:
```
https://instagram-clone-xxxx.onrender.com/login.html
```

## Important Notes

### MongoDB Connection
✅ Your MongoDB Atlas connection is already configured in `app.py`
✅ No changes needed - it will work automatically on Render

### Security Recommendations for Production

⚠️ **IMPORTANT**: Before going live, update `app.py`:

1. **Change Secret Key** (line 7):
   ```python
   app.secret_key = os.environ.get('SECRET_KEY', 'your-super-secret-random-key-here')
   ```
   
2. **Add SECRET_KEY to Render**:
   - In Render dashboard → Environment
   - Add: `SECRET_KEY` = `generate-a-random-32-character-string`

3. **Enable HTTPS Only** (Render does this automatically ✅)

4. **Consider Password Hashing**:
   - Currently passwords are stored in plain text
   - For production, use `bcrypt` or similar

### Free Tier Limitations

- ⏰ **Sleeps after 15 min** of inactivity
- 🐌 **First request** after sleep takes ~30 seconds
- 💾 **750 hours/month** free (enough for testing)
- 🔄 **Auto-deploys** on every git push

### Keeping It Awake (Optional)

Use a service like:
- **UptimeRobot** (https://uptimerobot.com) - Free
- **Cron-job.org** (https://cron-job.org) - Free
- Ping your site every 10 minutes

## Troubleshooting

### Build Fails
- Check build logs in Render dashboard
- Verify `requirements.txt` is correct
- Ensure Python version matches

### Site Not Loading
- Check if service is "Live" in Render dashboard
- View logs for errors
- Verify MongoDB connection string

### MongoDB Connection Issues
- Whitelist Render's IP in MongoDB Atlas:
  - Go to MongoDB Atlas → Network Access
  - Click "Add IP Address"
  - Select "Allow Access from Anywhere" (0.0.0.0/0)

## Auto-Deploy on Git Push

Once connected, every time you push to GitHub:
```bash
git add .
git commit -m "Update feature"
git push
```

Render automatically:
1. Detects the push
2. Rebuilds your app
3. Deploys the new version
4. Takes ~2-3 minutes

## Monitoring

View in Render Dashboard:
- 📊 **Metrics**: CPU, Memory usage
- 📝 **Logs**: Real-time application logs
- 🔄 **Deploys**: History of all deployments
- ⚙️ **Settings**: Environment variables, scaling

## Custom Domain (Optional)

1. Buy a domain (Namecheap, GoDaddy, etc.)
2. In Render → Settings → Custom Domains
3. Add your domain
4. Update DNS records as shown
5. SSL certificate auto-generated ✅

## Support

- Render Docs: https://render.com/docs
- Community: https://community.render.com
- MongoDB Atlas: https://www.mongodb.com/docs/atlas/

---

**Your app is now live! 🎉**

Share your Render URL and start collecting data in MongoDB!
