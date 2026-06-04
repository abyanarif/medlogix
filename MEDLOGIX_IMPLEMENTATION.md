    # MedLogix - Smart Pharmaceutical Management System
## MVP Implementation Guide

### 🎯 Project Overview
MedLogix is a comprehensive Smart Pharmaceutical Management System built with **Laravel 11, SQLite, and Tailwind CSS**. The system provides robust CRUD operations for three core Tier 1 features and four simulated Tier 2 features for pharmaceutical management, drug expiry tracking, and safe disposal guidance.

---

## 📋 TIER 1: FULLY FUNCTIONAL FEATURES (Implemented with Complete Business Logic)

### ✅ Feature A: Medicine Search & Detail View
**Route:** `GET /medicines` and `GET /medicines/{id}`
**Controllers:** `MedicineController`

**Functionality:**
- Real-time search interface querying the medicines table
- Search by medicine name, indication, or form
- Display complete dynamic drug details with all attributes
- Pagination support (12 medicines per page)
- BPJS coverage badge display on each medicine

**Database Fields Used:**
- `medicines.name` - Medicine name
- `medicines.form` - Medicine form (Tablet, Kapsul, Sirup, etc.)
- `medicines.indication` - Medical indication
- `medicines.dosage` - Recommended dosage
- `medicines.is_bpjs_covered` - BPJS coverage status

**Views:**
- `resources/views/medicines/index.blade.php` - Search results
- `resources/views/medicines/show.blade.php` - Detailed view

---

### ✅ Feature B: Medicine Cabinet & Smart Expired Alert
**Routes:** 
- `GET /cabinets` - View cabinet
- `GET /cabinets/create` - Add medicine form
- `POST /cabinets` - Store medicine
- `DELETE /cabinets/{id}` - Remove medicine

**Controllers:** `MedicineCabinetController`

**BUSINESS LOGIC (Implemented in MedicineCabinet Model):**
```php
// Automatic calculation of remaining days
$remaining_days = expiry_date->diffInDays(Carbon::now());

// UI STATE 1: If remaining_days <= 90 → Danger Red (#EF4444) badge "Segera Musnahkan!"
// UI STATE 2: If remaining_days > 90 → Success Green (#10B981) badge "Aman"
// Extended Logic: If expired → Gray (#DC2626) "Expired"
```

**Attributes:**
- Automatic status determination based on Carbon date calculation
- Color-coded status badges (Green/Red/Gray)
- Statistics dashboard showing totals by status
- Delete functionality with authorization policy

**Views:**
- `resources/views/cabinets/index.blade.php` - Cabinet dashboard with statistics
- `resources/views/cabinets/create.blade.php` - Add medicine form

---

### ✅ Feature C: Cara Pemusnahan Obat (Safe Disposal Guide)
**Route:** `GET /disposal-guide`
**Controller:** `MedicineCabinetController@showDisposalGuide`

**Content:** Static informational view routing to `/disposal-guide` with complete step-by-step guide:
1. **Keluarkan dari kemasan** - Remove from original packaging
2. **Hancurkan** - Crush or destroy the medicine
3. **Campur tanah** - Mix with soil or sand
4. **Buang ke tempat sampah** - Dispose in trash with precautions

**Views:**
- `resources/views/cabinets/disposal-guide.blade.php` - Complete disposal guide

**Additional Features:**
- Do's and Don'ts section
- Alternative disposal methods (Apotek, Puskesmas, RSUD)
- Precautions and safety guidelines
- FAQ section

---

## 🎭 TIER 2: SIMULATED / DUMMY FEATURES (UI with Hardcoded Data)

### ✅ Feature A: BPJS Drug Coverage
**Integration:** Integrated into medicine detail view
**Implementation:** Relies entirely on boolean `is_bpjs_covered` in database
- Green "Ditanggung BPJS" badge when `is_bpjs_covered = true`
- Gray "Non-BPJS" badge when `is_bpjs_covered = false`
- Status displayed on:
  - Medicine search results
  - Medicine detail page
  - Medicine cabinet listing

---

### ✅ Feature B: AI Farmasis Chat
**Route:** `GET /pharmacist-chat` and `POST /pharmacist-chat/send`
**Controller:** `PharmacistChatController`

**Simulation:**
- Static UI chat interface
- Hardcoded response: **"Paracetamol relatif aman pada kehamilan dalam dosis terapi."**
- Returns same response regardless of user input
- AJAX-powered real-time chat display
- Example questions provided for user guidance

**Views:**
- `resources/views/features/pharmacist-chat.blade.php` - Interactive chat UI

---

### ✅ Feature C: Drug Take Back Locator
**Route:** `GET /take-back-locator`
**Controller:** `TakeBackLocatorController`

**Hardcoded Locations (3):**
1. **Apotek Kimia Farma**
   - Address: Jl. Pemuda No. 45, Jakarta
   - Distance: 2.5 km
   - Rating: 4.8/5.0

2. **Puskesmas Genteng**
   - Address: Jl. Genteng Kali No. 12, Surabaya
   - Distance: 5.3 km
   - Rating: 4.5/5.0

3. **RSUD Dr Soetomo**
   - Address: Jl. Mayjend Prof. Dr. Moestopo No. 6-8, Surabaya
   - Distance: 8.1 km
   - Rating: 4.7/5.0

**Features:**
- Location cards with address, phone, hours
- Google Maps integration links
- Procedure guide (4 steps)
- FAQ section
- Free program information

**Views:**
- `resources/views/features/take-back-locator.blade.php`

---

### ✅ Feature D: Drug Origin Tracker
**Route:** `GET /drug-tracker`
**Controller:** `DrugTrackerController`

**Static Timeline (Pabrik → PBF → Apotek → Pasien):**

1. **Pabrik** (May 2025)
   - Production in certified factories
   - GMP quality control standards
   - Batch numbering and dating

2. **PBF** (June 2025)
   - Pharmaceutical Bulk Distributor
   - Climate-controlled storage
   - Barcode tracking system

3. **Apotek** (July 2025)
   - Official pharmacy storage
   - Pharmacist verification
   - Standard storage conditions

4. **Pasien** (August 2025)
   - Patient receipt with verification
   - Original packaging intact
   - Purchase receipt proof

**Views:**
- `resources/views/features/drug-tracker.blade.php`

---

## 🗄️ DATABASE SCHEMA

### Users Table
```sql
- id: int (primary)
- name: string
- email: string (unique)
- password: string (hashed)
- role: enum('apoteker', 'dokter', 'pasien')
- email_verified_at: timestamp (nullable)
- remember_token: string (nullable)
- created_at, updated_at: timestamps
```

### Medicines Table
```sql
- id: int (primary)
- name: string
- form: string (Tablet, Kapsul, Sirup, etc.)
- indication: text
- dosage: string
- is_bpjs_covered: boolean
- created_at, updated_at: timestamps
```

### Medicine Cabinets Table
```sql
- id: int (primary)
- user_id: int (FK → users)
- medicine_id: int (FK → medicines)
- expiry_date: date
- created_at, updated_at: timestamps
- Unique constraint: (user_id, medicine_id)
```

---

## 📦 INITIALIZATION DATA

### Patient User (Seeded)
- **Email:** pasien@medlogix.com
- **Password:** password123
- **Role:** pasien
- **Name:** Pasien Demo

### Master Medicines (Seeded - 5 Total)

| # | Nama | Form | Indikasi | Dosis | BPJS |
|---|------|------|----------|-------|------|
| 1 | Paracetamol | Tablet | Penurun demam, pereda nyeri | 500-1000 mg/4-6 jam | ✓ |
| 2 | Amoxicillin | Kapsul | Antibiotik infeksi bakteri | 250-500 mg/8 jam | ✓ |
| 3 | Omeprazole | Kapsul | Asam lambung, tukak lambung | 20-40 mg/hari | ✗ |
| 4 | Ibuprofen | Tablet | Nyeri, demam, anti-inflamasi | 200-400 mg/4-6 jam | ✓ |
| 5 | CTM | Tablet | Antihistamin untuk alergi | 4 mg/4-6 jam | ✓ |

**Note:** Medicine_cabinets table is **100% EMPTY** for live demonstration purposes.

---

## 🔐 AUTHENTICATION & AUTHORIZATION

### Middleware
- `auth` - Protects cabinet routes
- Authorization Policy: `MedicineCabinetPolicy` for delete operations

### Routes Structure
```php
// Public Routes
GET  /                           # Welcome
GET  /medicines                  # Search medicines
GET  /medicines/{id}             # View medicine detail
GET  /disposal-guide             # View disposal guide
GET  /pharmacist-chat            # AI chat interface
POST /pharmacist-chat/send       # Send chat message
GET  /take-back-locator          # View locations
GET  /drug-tracker               # View tracking timeline

// Protected Routes (auth middleware)
GET  /cabinets                   # View my cabinet
GET  /cabinets/create            # Add medicine form
POST /cabinets                   # Store medicine
DELETE /cabinets/{id}            # Remove medicine
```

---

## 🎨 UI/UX FEATURES

### Design System
- **Primary Color:** Blue (#0066CC)
- **Success Color:** Green (#10B981)
- **Danger Color:** Red (#EF4444)
- **Warning Color:** Orange (#F97316)
- **Framework:** Tailwind CSS CDN
- **Icons:** Font Awesome 6.4.0
- **Interactivity:** Alpine.js for reactive components

### Navigation
- Top navigation bar with logo and menu
- Role-based menu items
- Flash message alerts (success/error)
- Responsive design (mobile, tablet, desktop)
- Footer with links and information

### Key Views
1. Homepage with 8 feature cards
2. Medicine search with grid layout
3. Medicine detail with related information cards
4. Medicine cabinet dashboard with statistics
5. Add medicine form with smart validation
6. Disposal guide with visual steps
7. AI chat interface with example questions
8. Take back locator with location cards
9. Drug tracker with animated timeline

---

## 🚀 SETUP & DEPLOYMENT

### Prerequisites
- PHP 8.1+ (Laravel 11 requirement)
- Composer
- Node.js (optional, for Tailwind compilation)

### Installation

1. **Clone/Download Repository**
   ```bash
   cd c:\Tugas\Aurora\medlogix
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Setup**
   ```bash
   # Already configured in .env:
   DB_CONNECTION=sqlite
   DB_DATABASE=database.sqlite
   SESSION_DRIVER=cookie
   ```

4. **Database Setup**
   ```bash
   php artisan migrate --seed
   ```
   This creates:
   - All required tables
   - 1 patient user
   - 5 master medicines
   - Empty medicine_cabinets

5. **Start Development Server**
   ```bash
   php artisan serve
   ```
   Access at: `http://localhost:8000`

---

## 🧪 TEST CREDENTIALS

**Patient User:**
- Email: `pasien@medlogix.com`
- Password: `password123`

**Available Medicines to Test:**
1. Paracetamol (BPJS Covered)
2. Amoxicillin (BPJS Covered)
3. Omeprazole (Not Covered)
4. Ibuprofen (BPJS Covered)
5. CTM (BPJS Covered)

---

## 📚 KEY CLASSES & FILES

### Models
- `App\Models\User` - User with role support and relationships
- `App\Models\Medicine` - Medicine catalog
- `App\Models\MedicineCabinet` - User's medicine collection with smart expiry logic

### Controllers
- `MedicineController` - Search and detail
- `MedicineCabinetController` - CRUD operations with business logic
- `PharmacistChatController` - AI chat simulation
- `TakeBackLocatorController` - Location listing
- `DrugTrackerController` - Timeline display

### Migrations
- `0001_01_01_000000_create_users_table.php` - Updated with role enum
- `2025_01_01_000003_create_medicines_table.php` - Medicine catalog
- `2025_01_01_000004_create_medicine_cabinets_table.php` - User's cabinet

### Views
- `layouts/app.blade.php` - Master layout with navigation
- `medicines/{index,show}.blade.php` - Search and detail
- `cabinets/{index,create,disposal-guide}.blade.php` - Cabinet management
- `features/{pharmacist-chat,take-back-locator,drug-tracker}.blade.php` - Tier 2 features

### Routes
- `routes/web.php` - All 11 routes with proper middleware

---

## 🎯 BUSINESS LOGIC HIGHLIGHTS

### Smart Expiry Calculation (Core Logic)
```php
// In MedicineCabinet Model
public function getRemainingDaysAttribute(): int {
    return (int) $this->expiry_date->diffInDays(Carbon::now());
}

public function getStatusAttribute(): string {
    $remainingDays = $this->remaining_days;
    if ($remainingDays <= 0) return 'expired';
    if ($remainingDays <= 90) return 'danger';
    return 'safe';
}
```

### Status Badge Colors
- **Safe (> 90 days):** Green (#10B981)
- **Danger (≤ 90 days):** Red (#EF4444)
- **Expired (≤ 0 days):** Dark Red (#DC2626)

### Real-time Search
```php
Medicine::query()
    ->when($search, function ($query) use ($search) {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('indication', 'like', "%{$search}%")
            ->orWhere('form', 'like', "%{$search}%");
    })
    ->paginate(12);
```

---

## 📊 FEATURE CHECKLIST

### Tier 1: Fully Functional ✅
- [x] Medicine Search & Detail - Real-time search with pagination
- [x] Medicine Cabinet - CRUD operations for user's cabinet
- [x] Smart Expired Alert - Carbon-based expiry calculation with color-coded UI
- [x] Disposal Guide - Comprehensive step-by-step guide (4 steps + alternatives)

### Tier 2: Simulated ✅
- [x] BPJS Drug Coverage - Database-driven badges
- [x] AI Farmasis Chat - Static UI with hardcoded response
- [x] Drug Take Back Locator - 3 hardcoded locations
- [x] Drug Origin Tracker - Static timeline (Pabrik → PBF → Apotek → Pasien)

### Additional Features ✅
- [x] Robust MVC architecture
- [x] Eloquent model relationships
- [x] Authorization policies
- [x] Flash message notifications
- [x] Responsive Tailwind CSS design
- [x] Font Awesome icons integration
- [x] Bootstrap with sample data
- [x] Empty medicine_cabinets for live demo

---

## 🔍 VERIFICATION COMMANDS

### Check Seeded Data
```bash
php artisan tinker

# Verify medicines
App\Models\Medicine::count()  # Should return 5

# Verify user
App\Models\User::where('email', 'pasien@medlogix.com')->first()

# List all medicines
App\Models\Medicine::all()->pluck('name')
```

---

## 🎬 LIVE DEMONSTRATION STEPS

1. **Start Server:** `php artisan serve`
2. **Navigate to:** `http://localhost:8000`
3. **Click "Cari Obat"** → Search medicines
4. **Click Medicine** → View details with BPJS badge
5. **Login** with `pasien@medlogix.com` / `password123`
6. **Add Medicine to Cabinet** → Set future expiry date
7. **View Cabinet** → See status badges and statistics
8. **Test AI Chat** → Ask question, get hardcoded response
9. **View Disposal Guide** → See complete 4-step process
10. **Explore Take Back** → View 3 hardcoded locations
11. **Track Origin** → View complete supply chain timeline

---

## 📝 NOTES

- **Database:** SQLite (database.sqlite) - no MySQL needed
- **Session:** Cookie-based (SESSION_DRIVER=cookie)
- **Authentication:** Built-in Laravel Auth with role support
- **Medicine Cabinet:** Intentionally left empty for demonstration
- **Tier 2 Features:** Demonstrate UI design and user experience
- **Production Ready:** Can be extended with real API integrations

---

## ✨ Developed with
- **Laravel 11** - Backend framework
- **SQLite** - Database
- **Tailwind CSS** - Styling
- **Font Awesome** - Icons
- **Alpine.js** - Interactivity
- **Carbon** - Date calculations

---

**Status:** ✅ MVP Complete - Ready for Live Demonstration
