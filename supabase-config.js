// ====================================================================
// KASIR PINTAR - SUPABASE CLIENT CONFIGURATION
// ====================================================================
// INSTRUKSI:
// 1. Buka https://supabase.com/dashboard → pilih project Anda
// 2. Klik Settings → API
// 3. Salin "Project URL" dan "anon public" key
// 4. Tempel di bawah ini, ganti placeholder
// ====================================================================

const SUPABASE_URL = 'https://ngozmrcadbvpxeylxsus.supabase.co';
const SUPABASE_ANON_KEY = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im5nb3ptcmNhZGJ2cHhleWx4c3VzIiwicm9sZSI6ImFub24iLCJpYXQiOjE3ODA4MTkxNzEsImV4cCI6MjA5NjM5NTE3MX0._1JcKTBBDQa1aDLKq8TTgt6GD3cZqOL4BWWL6IwRM7M';

// Inisialisasi Supabase Client
const supabaseClient = window.supabase.createClient(SUPABASE_URL, SUPABASE_ANON_KEY);
