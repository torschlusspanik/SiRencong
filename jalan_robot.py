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
    # Menentukan lokasi file Excel di dalam folder uploads
    BASE_DIR = os.path.dirname(os.path.abspath(__file__))
    FILE_EXCEL = os.path.join(BASE_DIR, 'uploads', 'data_entry.xlsx')
    URL_SITUS = "https://siapp.dipendajatim.go.id/"

    try:
        if not os.path.exists(FILE_EXCEL):
            print(f"Error: File {FILE_EXCEL} tidak ditemukan!")
            return

        df = pd.read_excel(FILE_EXCEL)
        akun_unik = df['ID_Login'].unique()

        # Konfigurasi Browser
        options = webdriver.ChromeOptions()
        # options.add_argument("--headless") # Aktifkan jika ingin berjalan tanpa jendela
        driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)
        driver.maximize_window()
        wait = WebDriverWait(driver, 15)

        for id_user in akun_unik:
            data_akun = df[df['ID_Login'] == id_user]
            pw = data_akun.iloc[0]['Password']

           # --- PROSES LOGIN ---
            driver.get(URL_SITUS)
            
            # 1. Tunggu hingga kolom input muncul
            wait.until(EC.presence_of_element_located((By.ID, "blogin"))) # Menunggu tombol login muncul sebagai tanda halaman siap
            
            # 2. Cari semua elemen input
            inputs = driver.find_elements(By.TAG_NAME, "input")
            
            # Kolom 1: Kode Samsat (statis: 251)
            inputs[0].clear()
            inputs[0].send_keys("251")
            
            # Kolom 2: ID Login
            inputs[1].clear()
            inputs[1].send_keys(str(id_user))
            
            # Kolom 3: Password
            inputs[2].clear()
            inputs[2].send_keys(str(pw))
            
            # 3. Klik Tombol Login menggunakan ID 'blogin' yang sudah pasti
            tombol_login = driver.find_element(By.ID, "blogin")
            tombol_login.click()
            
            print(f"Sedang login dengan ID: {id_user}...")
            
            # 4. Tunggu transisi halaman setelah login
            time.sleep(5)

  # --- PROSES ENTRY DATA ---
            for _, row in data_akun.iterrows():
                try:
                    # 1. Klik menu SPSO (Gunakan Selector untuk teks link)
                    menu_spso = wait.until(EC.element_to_be_clickable((By.PARTIAL_LINK_TEXT, "SPSO")))
                    menu_spso.click()
                    time.sleep(1)

                    # 2. Klik Entry Data SPSO (Gunakan href=?id=11)
                    btn_entry = wait.until(EC.element_to_be_clickable((By.XPATH, "//a[@href='?id=11']")))
                    btn_entry.click()
                    time.sleep(1)

                    # 3 & 4. Klik dan Isi kolom Nomor Entry (ID: nspos)
                    input_entry = wait.until(EC.presence_of_element_located((By.ID, "nspos")))
                    input_entry.clear()
                    input_entry.send_keys(str(row['Nomor_Entry']))
                    
                    # 5. Klik Simpan (ID: savespos)
                    btn_simpan = driver.find_element(By.ID, "savespos")
                    btn_simpan.click()

                    print(f"✅ Sukses Simpan: {row['Nomor_Entry']} (Akun: {id_user})")
                    
                    # Berikan jeda sebentar agar sistem SIAPP selesai memproses simpan
                    time.sleep(2) 

                except Exception as e:
                    print(f"⚠️ Gagal entry {row['Nomor_Entry']}: {str(e)}")
                    # Refresh halaman jika robot tersesat
                    driver.get("https://siapp.dipendajatim.go.id/idxstaf.php")
                    time.sleep(2)

            # Logout/Hapus session sebelum berganti akun
            driver.delete_all_cookies()

        driver.quit()
        print("Selesai memproses semua data.")

    except Exception as e:
        print(f"Terjadi kesalahan sistem: {str(e)}")

if __name__ == "__main__":
    main()