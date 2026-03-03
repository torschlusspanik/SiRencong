import pandas as pd
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time
import os
import sys

# Mengatasi masalah karakter unik/emoji di CMD Windows
if sys.platform == "win32":
    import io
    sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

def main():
    BASE_DIR = os.path.dirname(os.path.abspath(__file__))
    FILE_EXCEL = os.path.join(BASE_DIR,'..', 'uploads', 'data_entry.xlsx')
    URL_SITUS = "https://siapp.dipendajatim.go.id/"

    try:
        if not os.path.exists(FILE_EXCEL):
            print(f"Error: File {FILE_EXCEL} tidak ditemukan!")
            return

        # Membaca Excel dengan paksa string agar ID Login tidak berubah jadi format ilmiah
        df = pd.read_excel(FILE_EXCEL, sheet_name='spos', dtype=str, engine='openpyxl')

        # --- PERBAIKAN: Mengisi sel kosong (NaN) dengan nilai dari baris atasnya ---
        #df = df.ffill()
        

        # --- TAMBAHKAN BARIS INI: MENGHENTIKAN LOOP JIKA DATA HABIS ---
        # Menghapus baris jika kolom 'NO ENTRI' kosong (berarti data sudah habis)
        df = df.dropna(subset=['NO ENTRI', 'ID_Login', 'Password'])
        
        
        # Memastikan tidak ada data kosong yang tersisa
        if df.empty:
            print("Info: Semua data di Excel sudah selesai diproses atau file kosong.")
            return
        # -------------------------------------------------------------
        
        # Inisialisasi Browser
        options = webdriver.ChromeOptions()
        driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)
        driver.maximize_window()
        wait = WebDriverWait(driver, 20)

        # Looping untuk setiap baris di Excel
        for index, row in df.iterrows():
            id_user = str(row['ID_Login']).strip()
            pw = str(row['Password']).strip()
            no_entri = str(row['NO ENTRI']).strip()

            # --- TAMBAHKAN LOGIKA PENGECEKAN DI SINI ---
            # Jika ID_Login atau Password kosong (bernilai 'nan' atau string kosong)
            if id_user.lower() == 'nan' or pw.lower() == 'nan' or not id_user or not pw:
                print(f"⏩ [{index+1}] Baris dilewati karena ID Login/Password kosong.")
                continue # Langsung lanjut ke baris berikutnya tanpa menjalankan kode di bawahnya
            # --------------------------------------------

            try:
                # --- PROSES LOGIN ---
                driver.get(URL_SITUS)
                wait.until(EC.presence_of_element_located((By.ID, "blogin")))
                
                inputs = driver.find_elements(By.TAG_NAME, "input")
                inputs[0].clear()
                inputs[0].send_keys("251") # Kode Samsat
                inputs[1].clear()
                inputs[1].send_keys(id_user) # ID Login
                inputs[2].clear()
                inputs[2].send_keys(pw) # Password
                
                driver.find_element(By.ID, "blogin").click()
                print(f"[{index+1}] Login ID: {id_user} untuk nomor: {no_entri}")
                sys.stdout.flush()
                time.sleep(3)

                # --- PROSES ENTRY DATA ---
                # 1. Klik menu SPSO
                menu_spso = wait.until(EC.element_to_be_clickable((By.PARTIAL_LINK_TEXT, "SPSO")))
                menu_spso.click()
                time.sleep(1)

                # 2. Klik Entry Data SPSO
                btn_entry = wait.until(EC.element_to_be_clickable((By.XPATH, "//a[@href='?id=11']")))
                btn_entry.click()
                time.sleep(1)

                # 3. Isi Nomor Entry
                input_entry = wait.until(EC.presence_of_element_located((By.ID, "nspos")))
                input_entry.clear()
                input_entry.send_keys(no_entri)
                
                # 4. Klik Simpan
                driver.find_element(By.ID, "savespos").click()

                # 5. Klik OK pada Pop-up Info (ID: btnModal)
                btn_ok = wait.until(EC.element_to_be_clickable((By.ID, "btnModal")))
                btn_ok.click()
                print(f"✅ Berhasil Simpan & OK: {no_entri}")
                
                # --- JEDA 3 DETIK SETELAH KLIK OK ---
                time.sleep(3) 

                # --- LOGOUT SESSION SETELAH JEDA ---
                driver.delete_all_cookies()
                print(f"🔄 Session Logout. Bersiap login data selanjutnya...\n")

            except Exception as e:
                print(f"⚠️ Gagal pada baris {index+1} ({no_entri}): {str(e)}")
                driver.delete_all_cookies()
                continue

        driver.quit()
        print("Selesai memproses semua data.")

    except Exception as e:
        print(f"Terjadi kesalahan sistem: {str(e)}")

if __name__ == "__main__":
    main()