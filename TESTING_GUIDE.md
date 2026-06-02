# MedLogix - Quick Testing Guide

## 🚀 Quick Start

```bash
cd c:\Tugas\Aurora\medlogix
php artisan serve
```

Access: **http://localhost:8000**

---

## 🔐 Login Credentials

```
Email: pasien@medlogix.com
Password: password123
```

---

## 📋 Feature Testing Checklist

### ✅ TIER 1: Fully Functional Features

#### Feature A: Medicine Search & Detail
1. Go to **"Cari Obat"** (Top Menu)
2. Try searching:
   - Search "Paracetamol" → Should show 1 result
   - Search "Tablet" → Should show 3 results (Paracetamol, Ibuprofen, CTM)
   - Search "Antibiotik" → Should show 1 result (Amoxicillin)
   - Empty search → Should show all 5 medicines
3. Click on any medicine → View full details
4. Verify BPJS badges:
   - ✓ Paracetamol = Green badge "Ditanggung BPJS"
   - ✓ Ibuprofen = Green badge
   - ✓ CTM = Green badge
   - ✗ Omeprazole = Gray badge "Non-BPJS"

---

#### Feature B: Medicine Cabinet & Smart Expired Alert
1. **Without Login:**
   - Go to "Lemari Obat" → Should redirect to login
   - Click "Login untuk Menambah" on medicine detail → Go to login

2. **After Login:**
   - Go to **"Lemari Obat"** → See empty cabinet with statistics (0 total)
   - Click **"Tambah Obat"** button
   
3. **Add First Medicine (TEST: Safe - Green):**
   - Select: **Paracetamol**
   - Set Expiry: **150 days from today** (e.g., if today is 2026-06-02, set 2026-11-30)
   - Click **"Simpan Obat"**
   - Should return to cabinet with medicine displayed
   - Status: **Green "Aman"** badge (remaining days > 90)
   - Statistics: Total=1, Aman=1, Segera=0, Expired=0

4. **Add Second Medicine (TEST: Danger - Red):**
   - Click "Tambah Obat"
   - Select: **Amoxicillin**
   - Set Expiry: **60 days from today** (e.g., 2026-08-01)
   - Click **"Simpan Obat"**
   - Status: **Red "Segera Musnahkan!"** badge (30 < remaining days ≤ 90)
   - Statistics: Total=2, Aman=1, Segera=1, Expired=0
   - "Panduan Pembuangan" button should appear

5. **Add Third Medicine (TEST: Already Expired):**
   - Click "Tambah Obat"
   - Select: **Omeprazole**
   - Set Expiry: **Yesterday's date** (e.g., 2026-06-01)
   - Click **"Simpan Obat"**
   - Status: **Dark Red "Expired"** badge
   - Statistics: Total=3, Aman=1, Segera=1, Expired=1

6. **Test Actions:**
   - Click **"Detail"** → Should view medicine details
   - Click **"Panduan Pembuangan"** (on Danger/Expired items) → Go to disposal guide
   - Click **"Hapus"** → Confirm deletion → Should be removed

---

#### Feature C: Cara Pemusnahan Obat (Safe Disposal Guide)
1. From Cabinet: Click **"Panduan Pembuangan"** on a medicine with red badge
2. Or directly visit: Menu → **"Panduan Pembuangan"**
3. Verify content:
   - [ ] Step 1: "Keluarkan dari Kemasan" with instructions
   - [ ] Step 2: "Hancurkan Obat" with tools list
   - [ ] Step 3: "Campur dengan Tanah" with ratio guidance
   - [ ] Step 4: "Buang ke Tempat Sampah" with warnings
   - [ ] Do's section (5 items)
   - [ ] Don'ts section (5 items)
   - [ ] Precautions highlighted
   - [ ] Alternative methods section

---

### 🎭 TIER 2: Simulated Features

#### Feature A: BPJS Drug Coverage
**Verification Across All Views:**
1. **Search Results:** Each medicine shows BPJS badge
2. **Medicine Detail:** Large BPJS status card (Green or Gray)
3. **Cabinet:** BPJS status displayed in grid
4. **Expected Results:**
   - Paracetamol: ✓ Green
   - Amoxicillin: ✓ Green
   - Omeprazole: ✗ Gray
   - Ibuprofen: ✓ Green
   - CTM: ✓ Green

---

#### Feature B: AI Farmasis Chat
1. Click **"Konsultasi AI"** (Top Menu)
2. Try different questions:
   - Type: "Apakah paracetamol aman?"
   - Type: "Apa dosis amoxicillin?"
   - Type: "Hello"
   - Type: Random text
3. **Expected Result:** ALL return same response:
   ```
   "Paracetamol relatif aman pada kehamilan dalam dosis terapi."
   ```
4. Verify UI:
   - [ ] Chat window displays
   - [ ] User messages appear on right (red background)
   - [ ] AI responses appear on left (blue background)
   - [ ] Timestamp shown for AI responses
   - [ ] Example questions clickable
   - [ ] Disclaimer displayed
   - [ ] Input form and send button working

---

#### Feature C: Drug Take Back Locator
1. Click **"Lokasi Tukar Obat"** (Top Menu)
2. Should display **3 hardcoded locations:**

   **Location 1: Apotek Kimia Farma**
   - Address: Jl. Pemuda No. 45, Jakarta
   - Distance: 2.5 km
   - Rating: 4.8/5.0
   - Phone button clickable
   - Maps button clickable

   **Location 2: Puskesmas Genteng**
   - Address: Jl. Genteng Kali No. 12, Surabaya
   - Distance: 5.3 km
   - Rating: 4.5/5.0

   **Location 3: RSUD Dr Soetomo**
   - Address: Jl. Mayjend Prof. Dr. Moestopo No. 6-8, Surabaya
   - Distance: 8.1 km
   - Rating: 4.7/5.0

3. Verify sections:
   - [ ] 4-step procedure guide with icons
   - [ ] FAQ section with 4 Q&A
   - [ ] Maps and phone links

---

#### Feature D: Drug Origin Tracker
1. Click **"Tracking"** (Top Menu) or "Tracking Obat" on homepage
2. Should display **4-stage timeline:**

   **Stage 1: Pabrik** (May 2025)
   - Icon: 🏭
   - Description about production

   **Stage 2: PBF** (June 2025)
   - Icon: 📦
   - Description about distribution

   **Stage 3: Apotek** (July 2025)
   - Icon: 💊
   - Description about pharmacy storage

   **Stage 4: Pasien** (August 2025)
   - Icon: 👤
   - Description about patient receipt

3. Verify all sections displayed:
   - [ ] Timeline with visual connectors
   - [ ] 4 detailed information cards
   - [ ] Security features section (4 items)
   - [ ] Verification methods section

---

## 🎨 UI/UX Verification

- [ ] Responsive design (test on mobile, tablet, desktop)
- [ ] Navigation bar displays correctly
- [ ] Flash messages appear for success/error
- [ ] All buttons have hover effects
- [ ] Colors match specification:
  - Safe: Green (#10B981)
  - Danger: Red (#EF4444)
  - Expired: Dark Red (#DC2626)
- [ ] Font Awesome icons display correctly
- [ ] Footer visible on all pages
- [ ] Login/Logout working

---

## 🔄 User Flow Test Scenario

**Complete User Journey:**

1. ✅ Visit homepage
2. ✅ Search for "Paracetamol" without logging in
3. ✅ View medicine detail
4. ✅ Click "Login untuk Menambah"
5. ✅ Login with provided credentials
6. ✅ Navigate to medicine cabinet (should be empty)
7. ✅ Add 3 medicines with different expiry dates:
   - Paracetamol (150 days) → Green
   - Amoxicillin (60 days) → Red
   - Omeprazole (expired) → Gray
8. ✅ View statistics updating
9. ✅ Click disposal guide for red medicine
10. ✅ Return to cabinet
11. ✅ View AI chat
12. ✅ Ask multiple questions → get same response
13. ✅ View take back locations
14. ✅ View drug tracker timeline
15. ✅ Search different medicines
16. ✅ Logout

---

## 🐛 Common Test Cases

### Search Tests
- [ ] Search with empty string (should show all)
- [ ] Search with partial name ("Parac")
- [ ] Search with indication ("Demam")
- [ ] Search with form ("Tablet")
- [ ] Search non-existent ("Xyz")
- [ ] Pagination working (12 per page)

### Cabinet Tests
- [ ] Add medicine without expiry date (should error)
- [ ] Add medicine with past date (should error)
- [ ] Add same medicine twice (should work - composite key allows)
- [ ] Delete medicine (should remove)
- [ ] Statistics update correctly
- [ ] Status badges change correctly

### Auth Tests
- [ ] Access cabinet without login (redirect)
- [ ] Add medicine without login (redirect)
- [ ] Delete someone else's medicine (should be authorized)

---

## 📊 Database Verification

```bash
php artisan tinker

# Check medicines
App\Models\Medicine::count()  # Should be 5

# Check user
App\Models\User::count()  # Should be 1

# Check cabinet
App\Models\MedicineCabinet::count()  # Should be 0 (empty)

# View specific medicine
App\Models\Medicine::where('name', 'Paracetamol')->first()

# Check BPJS status
App\Models\Medicine::where('is_bpjs_covered', true)->count()  # Should be 4
```

---

## ⚙️ Troubleshooting

### Issue: Database not found
**Solution:** Run `php artisan migrate --seed` again

### Issue: Migrations already run error
**Solution:** Either:
1. Delete `database/database.sqlite` and rerun migrations
2. Or run `php artisan migrate:fresh --seed`

### Issue: Medicine not found when adding
**Solution:** Refresh page or clear Laravel cache: `php artisan cache:clear`

### Issue: Styles not loading (Tailwind)
**Solution:** CDN version used, should load automatically. Check internet connection.

### Issue: Icons not showing
**Solution:** Font Awesome CDN. Check internet connection.

---

## ✨ Success Indicators

When everything is working correctly:
- ✅ All 5 medicines display in search
- ✅ BPJS badges show correct colors
- ✅ Status badges change based on expiry date calculation
- ✅ Statistics update in real-time
- ✅ All views display responsive design
- ✅ Navigation accessible from all pages
- ✅ AI chat returns consistent response
- ✅ 3 locations visible in take back section
- ✅ Timeline displays all 4 stages
- ✅ Disposal guide shows 4 steps + alternatives

---

## 📸 Expected Screenshots

### Homepage
- 8 feature cards in grid
- Hero section with call-to-action buttons

### Medicine Search
- Search bar at top
- Grid of medicine cards
- BPJS badges on each card
- Pagination at bottom

### Medicine Detail
- Large BPJS badge on right
- Indikasi section
- Dosis section highlighted in blue
- Related information cards at bottom
- "Tambah ke Lemari Obat" button

### Medicine Cabinet
- 4 statistics boxes at top
- Medicine list with status badges
- Action buttons (Detail, Disposal Guide, Delete)

### Disposal Guide
- 4 numbered steps with icons
- Do's and Don'ts sections
- Alternative methods box
- Emergency contacts

### AI Chat
- Chat window with messages
- Example questions
- Disclaimer box

### Take Back Locator
- 3 location cards
- Map and phone buttons
- 4-step procedure
- FAQ section

### Drug Tracker
- Visual timeline with 4 stages
- Connected with vertical line
- Detail cards for each stage
- Security features grid

---

**Last Updated:** 2026-06-02
**System Status:** ✅ Ready for Testing
