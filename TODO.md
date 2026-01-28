# Fix Mahasiswa Field in PenilaianForm.php

## Tasks
- [x] Rename Select field from 'id_mata_kuliah' to 'jadwal_kuliah_id'
- [x] Update options function to use $get('jadwal_kuliah_id')
- [x] Update afterStateUpdated to be on 'jadwal_kuliah_id'
- [x] Fix options function to use correct field name
- [x] Change reactive() to live() for schema compatibility
- [x] Update options function to use $state instead of Get $get
