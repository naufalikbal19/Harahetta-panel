# Update Loan Table Columns

**Current DB fields:** id, nama_peminjam, jumlah_pinjaman, status, tanggal_pinjam, keterangan, created_at, updated_at
**Requested columns:** Checkbox, ID, Order Number, Username, Phone Number, Uid, Loan Amount (jumlah_pinjaman), Loan Period, Sign, Application Time (tanggal_pinjam), Status, Aksi

**Information Gathered:**
- Missing DB fields: order_number, username, phone_number, uid, loan_period (int days?), sign (varchar path?)
- dashboard.php needs thead, DataTable columns, checkbox JS, modal form updates.

**Plan:**
1. [x] Update db.sql: Add new columns + sample data. ✅
2. [x] Edit dashboard.php: Table headers, DataTable columns w/ checkbox, select JS. ✅
3. [x] Add new form fields to modal + populate on edit. ✅
4. Add bulk select JS.

**Dependent Files:** db.sql, dashboard.php

**Complete ✅**
- [x] DB schema + dummy data (8 records)
- [x] Table columns, checkbox
- [x] Modal form fields + edit populate

Import db.sql, reload dashboard.php - table full with data! 

Data dummy sudah ditambah (8 records). Jalankan db.sql & test! 🎉
