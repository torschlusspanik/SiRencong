import pandas as pd
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time
import os

def main():
    BASE_DIR = os.path.dirname(os.path.abspath(__file__))
    FILE_EXCEL = os.path.join(BASE_DIR, 'uploads', 'data_entry.xlsx')
    
    try:
        # 1. Baca Excel SIAPP (Kolom: NO ENTRI sebagai Teks)
        df = pd.read_excel(FILE_EXCEL, dtype={'NO ENTRI': str})
        df = df.dropna(subset=['NO ENTRI'])
        
        # 2. Inisialisasi Browser
        driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()))
        driver.maximize_window()
        wait = WebDriverWait(driver, 15) # Tunggu maksimal 15 detik

        # Buka Dashboard (Silakan Login Manual di sini)
        driver.get("https://siapp.dipendajatim.go.id/idxstaf.php")
        print("💡 Silakan Login manual. Robot akan mulai bekerja setelah menu SPSO muncul.")

        # 3. Looping Data
        for index, row in df.iterrows():
            no_entri = row['NO ENTRI'].strip()
            
            try:
                # LANGKAH 1: Klik Menu SPSO
                menu_spso = wait.until(EC.element_to_be_clickable((By.PARTIAL_LINK_TEXT, "SPSO")))
                menu_spso.click()
                time.sleep(0.5)

                # LANGKAH 2: Klik Entry Data SPSO
                btn_entry = wait.until(EC.element_to_be_clickable((By.XPATH, "//a[@href='?id=11']")))
                btn_entry.click()

                # LANGKAH 3 & 4: Isi Nomor Entry
                input_field = wait.until(EC.presence_of_element_located((By.ID, "nspos")))
                input_field.clear()
                input_field.send_keys(no_entri)
                
                # LANGKAH 5: Klik Simpan
                btn_simpan = driver.find_element(By.ID, "savespos")
                btn_simpan.click()
                print(f"⌛ Memproses Nomor: {no_entri}...")

                # --- PENANGANAN POP-UP INFO ---
                # Robot menunggu tombol 'OK' muncul di layar
                btn_ok = wait.until(EC.element_to_be_clickable((By.ID, "btnModal")))
                btn_ok.click()
                
                print(f"✅ [{index+1}] Sukses & OK: {no_entri}")
                
                # Jeda sebentar setelah halaman refresh otomatis (onclick=refreshpage)
                time.sleep(2)

            except Exception as e:
                print(f"⚠️ Kendala di baris {index+1} ({no_entri}): {str(e)}")
                driver.get("https://siapp.dipendajatim.go.id/idxstaf.php")
                time.sleep(2)

        print("\n🏁 SEMUA DATA TELAH SELESAI!")

    except Exception as e:
        print(f"❌ Gagal sistem: {str(e)}")

if __name__ == "__main__":
    main()