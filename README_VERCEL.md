# Vercel Deployment Guide for Elope (Laravel Project)

আপনার Laravel প্রজেক্টটি Vercel-এ **এক ক্লিকে (One-Click) deploy** করার জন্য সম্পূর্ণ প্রস্তুত করা হয়েছে।

---

## 🚀 কিভাবে Deploy করবেন (২টি সহজ উপায়)

### উপায় ১: GitHub Repositories এর মাধ্যমে (সবচেয়ে সহজ)

1. আপনার **elop** প্রজেক্টটি GitHub-এ push করুন (`git push origin main`)
2. [Vercel Dashboard](https://vercel.com/dashboard)-এ যান এবং **"Add New" > "Project"** এ ক্লিক করুন।
3. আপনার GitHub repository সিলেক্ট করুন।
4. **Deploy** বাটনে ক্লিক করুন! 
   *(সকল Vercel Serverless routing এবং sqlite database automatic configure হয়ে যাবে)*

---

### উপায় ২: Vercel CLI দিয়ে সরাসরি Terminal থেকে

1. আপনার কম্পিউটারে Vercel CLI না থাকলে ইনস্টল করুন:
   ```bash
   npm install -g vercel
   ```
2. প্রজেক্ট ফোল্ডারে কমান্ড দিন:
   ```bash
   vercel
   ```
3. প্রথমবার লগইন এবং প্রম্পটে Enter চেপে নিশ্চিত করুন।
4. Production-এ deploy করার জন্য:
   ```bash
   vercel --prod
   ```

---

## 🛠️ কি কি তৈরি এবং সেটআপ করা হয়েছে?

1. **`vercel.json`**: Vercel Serverless Function (`vercel-php@0.6.0` runtime) কনফিগার করা হয়েছে।
2. **`api/index.php`**: Vercel-এর read-only filesystem সমস্যা দূর করার জন্য `/tmp` ফোল্ডারে writable views, cache এবং SQLite DB অটোমেটিক হ্যান্ডেল করার লজিক যুক্ত করা হয়েছে।
3. **`.vercelignore`**: অনাবশ্যক `vendor`, `node_modules`, `.env` ফাইলগুলো deploy করার সময় বাদ দেয়ার ব্যবস্থা করা হয়েছে, যা Deployment স্পিড বহুগুণ বাড়িয়ে দিবে।

---

## 🔐 Environment Variables (প্রয়োজন হলে)

Vercel Dashboard এর **Settings > Environment Variables** এ গিয়ে আপনি ইচ্ছে করলে নিচের Key-গুলো কাস্টমাইজ করতে পারেন:

| Key | Description | Default Value |
|---|---|---|
| `APP_KEY` | Laravel Encryption Key | (Default key auto set) |
| `APP_ENV` | Environment Mode | `production` |
| `APP_DEBUG` | Debug Mode | `true` |
| `DB_CONNECTION` | Database Type | `sqlite` |

---

✅ **এখন আপনি ফাইল জাস্ট Push/Upload করলেই Vercel-এ লাইভ হয়ে যাবে!**
