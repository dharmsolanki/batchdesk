# BatchDesk — Batch Production · GST Billing · QR-Verified COA

Multi-tenant SaaS for small manufacturers (pharma, ayurvedic, cosmetics, food/FSSAI,
chemicals, soap) that combines batch-wise production, GST invoicing and one-click
**Certificates of Analysis with QR verification** in a single tool.

## The differentiator: QR-Verified COA
Every COA carries a QR code. When a buyer scans it, a public verification page
(`/verify/{token}`) opens showing the original test results, issuer, and timestamps.
Edited or forged COAs are immediately detectable. Large ERPs do not offer this.

## Core workflow (under 2 minutes per batch)
1. **Product + Specification** (one-time): create a product and define its COA
   parameters (pH, Moisture, Assay, etc.)
2. **Raw material lots**: receive supplier lots for traceability
3. **Create a batch**: link the material lots consumed → status: TESTING
4. **Enter test results**: spec is pre-loaded, enter results + Pass/Fail →
   all passing = auto-RELEASED
5. **Generate COA**: letterhead + results table + signatories + QR → Print/PDF/WhatsApp
6. **Invoice**: select released batches → batch numbers print on the invoice,
   COA verification links are included in the WhatsApp message

## Built-in compliance logic
- Only RELEASED batches can be invoiced (testing/rejected stock cannot be sold)
- Batch numbers print automatically on invoices — recall-trace ready
- Full chain recorded: supplier lot → batch → customer
- Expiry alerts on the dashboard (45 days ahead)
- Split payments (cash + UPI + bank), outstanding balance tracking

## Local setup
```bash
composer create-project laravel/laravel:^11.0 batchdesk
cd batchdesk
# Copy the files from this package into the project
# (User.php and routes/web.php will be overwritten; everything else is new)
# In .env set: DB_CONNECTION=sqlite (remove the other DB_ lines) — easiest option
php artisan migrate
php artisan serve
```
Open `http://localhost:8000/register`, create your company, then start with Products.

> Do NOT delete Laravel's default migrations (`0001_01_01_...`). This package's
> migration adds `company_id` to the users table and runs after them.

## Suggested first test: a soap business
- Product: "Neem Soap 50g" — Spec: pH (8.0–9.0), Appearance (Complies), Weight (48–52 g)
- Material lots: Soap Base, Neem Oil, Vitamin E
- Create a batch, enter the pH result, print the COA — ship it with the product.

## Hostinger deployment
Same process as any Laravel app on shared hosting: `composer install --no-dev`
locally, upload, adjust `public/index.php` paths, set production `.env`,
run `php artisan migrate --force`. A VPS is recommended once you have 5+ paying tenants.

## Tech notes
- Multi-tenancy: `BelongsToCompany` trait (global scope on `company_id`)
- QR codes render client-side via the qrcodejs CDN (no Composer dependency)
- Tailwind/Alpine via CDN — fine for MVP; switch to a local build for production
- PWA manifest included — add `icon-192.png` and `icon-512.png` to `public/`

## Phase 2 roadmap
Customer-specific COA specs, emailed COA PDFs, e-invoice/e-way bill, purchase
orders, Razorpay Subscriptions (₹1,499–2,999/month plans), staff roles,
audit log, one-click mock-recall report.

## Admin Panel & Trial Management

### Creating your admin account
After `php artisan migrate`, register normally at `/register` then run:
```bash
php artisan tinker
# Inside tinker:
User::where('email', 'your@email.com')->update(['is_admin' => true]);
```
Admin users see an "Admin panel" button in the header. They bypass subscription checks.

### Admin panel URL
`/admin` — sidebar with Dashboard and All Companies.

### Managing a company (the Option A flow)
1. Customer signs up → gets 30-day trial automatically
2. Trial expires → they see the "Trial ended" page with WhatsApp contact
3. They contact you, you receive payment (UPI/bank)
4. Go to `/admin/companies/{id}` → "Activate subscription" → choose Monthly/Yearly → note the payment reference → click Activate
5. Company gets access immediately

### Other admin actions
- **Extend trial** — add 7/14/30/60/90 days (useful for demos or follow-up)
- **Deactivate** — suspend immediately, data preserved
- **Admin notes** — free-text notepad per company (payment refs, call notes, any info)

### Trial banner
When a trial has 10 or fewer days left, a banner appears at the top of every app page with a WhatsApp contact link.
