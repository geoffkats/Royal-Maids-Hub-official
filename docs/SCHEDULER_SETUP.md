# Laravel Task Scheduler Setup - Production Deployment

## Overview

The Royal Maids Hub application uses Laravel's Task Scheduler to run automated jobs, including:
- **Contract Expiring Notifications** (daily at 9:00 AM)
- **CRM Activity Digest** (daily at 8:00 AM)
- **SLA Breach Checks** (every hour)
- **Auto Backups** (daily at 2:00 AM)
- **CRM Automation Processing** (every hour)

For these to work automatically in production, you **must** set up a cron job on your server.

---

## Production Setup Requirements

### For Shared Hosting (Most Common)

**Your hosting provider's cPanel/Dashboard:**

1. **Log into cPanel**
2. **Navigate to**: Cron Jobs (or Advanced > Cron Jobs)
3. **Add New Cron Job**:
   - **Common Settings**: Every Minute
   - **Command**:
     ```
     /usr/bin/php /home/username/public_html/royal-maids-hub/artisan schedule:run >> /dev/null 2>&1
     ```

**Important Notes**:
- Replace `/home/username/public_html` with your actual path
- The command must point to your **artisan** file
- `>> /dev/null 2>&1` suppresses output (optional, but clean)

---

### For VPS/Dedicated Server

**SSH into your server and edit crontab:**

```bash
crontab -e
```

**Add this line:**
```
* * * * * cd /var/www/royal-maids-hub && php artisan schedule:run >> /dev/null 2>&1
```

**Save by pressing**:
- `Ctrl + X` (Nano editor)
- `:wq` (Vim editor)

**Verify the cron was added:**
```bash
crontab -l
```

---

### For Docker/Container Deployment

**In your Docker container's startup script:**

```bash
# Add cron job
echo "* * * * * cd /app && php artisan schedule:run >> /dev/null 2>&1" | crontab -

# Start cron daemon
crond -f
```

Or in `Dockerfile`:
```dockerfile
RUN echo "* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1" | crontab -
CMD ["crond", "-f"]
```

---

## Verifying the Scheduler Works

### Method 1: Check Cron Logs (Linux/VPS)

```bash
# On most systems
tail -f /var/log/syslog | grep CRON

# Or on CentOS
tail -f /var/log/cron
```

You should see entries like:
```
Feb  4 09:00:01 server CRON[12345]: (root) CMD (cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1)
```

### Method 2: Check Application Logs

SSH into your server and check:
```bash
tail -f storage/logs/laravel.log
```

You should see scheduled job execution logs.

### Method 3: Manual Test

SSH into your server and run manually:
```bash
cd /var/www/royal-maids-hub
php artisan schedule:run -v
```

Output should show:
```
Running scheduled command: 'contracts:send-expiring-notifications --days=30'
Running scheduled command: 'crm:daily-activity-digest'
```

### Method 4: Database Check

For contract notifications, check if emails were queued:
```bash
# SSH to server
php artisan tinker

# In tinker:
DB::table('notifications')->where('created_at', '>=', now()->subMinutes(5))->count()
```

This shows notifications created in the last 5 minutes.

---

## Current Scheduled Tasks

All tasks are defined in `routes/console.php`:

| Task | Schedule | Time | Purpose |
|------|----------|------|---------|
| Contract Expiring Notifications | Daily | 09:00 AM | Notify admins/trainers of expiring contracts |
| Daily Activity Digest | Daily | 08:00 AM | Send activity summaries |
| SLA Breach Checks | Hourly | Every hour | Check for missed deadlines |
| CRM Auto Backup | Daily | 02:00 AM | Automated database backup |
| CRM Automation | Hourly | Every hour | Process automation rules |
| Stale Leads Followup | Weekly | Monday 09:00 AM | Create followups for inactive leads |

---

## Troubleshooting

### Emails Not Sending

**Check:**
1. Is the cron job running? (Verify in logs)
2. Is `MAIL_DRIVER` set correctly in `.env`?
3. Are there contracts expiring in the next 30 days?
4. Do admins/trainers exist in the database?

**Test manually:**
```bash
php artisan contracts:send-expiring-notifications -v
```

### Cron Job Not Running

**Common issues:**
- PHP path is wrong → Get correct path: `which php`
- Working directory path is wrong → Use absolute path
- Cron service not running → Service might be disabled

**Fix:**
```bash
# Check if cron is running
service cron status

# Restart if needed
service cron restart

# Verify crontab was saved
crontab -l
```

### "Command Not Found" Error

**Fix**: Use full PHP path
```bash
/usr/bin/php /path/to/artisan schedule:run
```

Get your PHP path:
```bash
which php
```

---

## Production Checklist

- [ ] Cron job added to server
- [ ] Cron job verified with `crontab -l`
- [ ] `.env` has correct `MAIL_DRIVER` setting
- [ ] `.env` has correct `MAIL_*` credentials
- [ ] Application database is accessible from cron
- [ ] `storage/logs/` directory is writable
- [ ] Tested manually with `php artisan schedule:run -v`
- [ ] Checked server logs for execution
- [ ] Verified notifications are being sent
- [ ] Set up log monitoring/alerts (optional)

---

## Optional: Log Rotation

To prevent logs from growing too large, add a log rotation:

**Linux/VPS - Create `/etc/logrotate.d/royal-maids-hub`:**
```
/var/www/royal-maids-hub/storage/logs/*.log {
    daily
    rotate 7
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
}
```

---

## Optional: Monitoring

### Email Alerts on Failure

Add to `routes/console.php`:
```php
Schedule::command('contracts:send-expiring-notifications --days=30')
    ->dailyAt('09:00')
    ->timezone('Africa/Kampala')
    ->emailOnFailure('admin@royalmaidshub.com');
```

### Slack Notifications

```php
Schedule::command('contracts:send-expiring-notifications --days=30')
    ->dailyAt('09:00')
    ->timezone('Africa/Kampala')
    ->sendOutputTo('/var/www/logs/contracts.log')
    ->onSuccess(function () {
        // Log success
    })
    ->onFailure(function () {
        // Notify Slack
    });
```

---

## Support

If you need help setting up the scheduler:
1. **Contact your hosting provider** - They can add the cron job for you
2. **Verify the command path** - They'll provide the correct PHP path
3. **Test the command** - They can run it manually to verify it works

**Typical hosting provider paths:**
- **Bluehost/GoDaddy**: `/usr/bin/php`
- **DigitalOcean**: `/usr/bin/php` or `/usr/local/bin/php`
- **Heroku**: Use Procfile instead
- **AWS**: Use CloudWatch Events or Lambda

---

## Questions?

- What hosting provider are you using?
- Do you need help finding your PHP path?
- Do you need help accessing cPanel/crontab?

I can help you with the specific setup!
