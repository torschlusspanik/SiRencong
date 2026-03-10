import re
import sys
import json
import pandas as pd
import os

def parse_raw_text(raw_text):
    # Pecah teks berdasarkan baris
    lines = [line.strip() for line in raw_text.split('\n') if line.strip()]
    data_list = []
    
    # Kita asumsikan setiap record terdiri dari 2 baris
    # Baris 1: No | No_entry | Nopol | Nama
    # Baris 2: Dusun/Alamat | Desa | Kec | Potensi | Tgl
    
    i = 0
    while i < len(lines):
        try:
            line1 = lines[i]
            # Ambil baris kedua jika ada
            line2 = lines[i+1] if (i+1) < len(lines) else ""
            
            # Gabungkan dengan pemisah tab/spasi agar mudah di split
            combined = line1 + " " + line2
            parts = combined.replace('\t', ' ').split()
            
            # Ekstrak berdasarkan posisi yang konsisten di data Anda
            # 1: No, 2: No_entry, 3: Nopol, dst
            data_list.append({
                "No": parts[0],
                "No_entry": parts[1],
                "Nopol": f"{parts[2]} {parts[3]} {parts[4]}",
                "Nama": " ".join(parts[5:parts.index(lines[1].split()[0])]) if len(parts) > 6 else parts[5],
                "Alamat": lines[1].split('\t')[0] if '\t' in line2 else line2.split()[0],
                "Desa": lines[1].split('\t')[1] if '\t' in line2 else line2.split()[1],
                "Kecamatan": lines[1].split('\t')[2] if '\t' in line2 else line2.split()[2],
                "Potensi": lines[1].split('\t')[3] if '\t' in line2 else line2.split()[3],
                "Tg_cetak": lines[1].split('\t')[4] if '\t' in line2 else line2.split()[4]
            })
            i += 2 # Lompat ke record berikutnya
        except:
            i += 1 # Jika baris rusak, coba lompat 1
            
    return data_list

# Bagian bawah (if name main) tetap sama untuk output JSON
# Bagian bawah (if __name__ == "__main__") tetap sama seperti sebelumnya...

if __name__ == "__main__":
    if len(sys.argv) > 1:
        input_string = sys.argv[1]
        hasil = parse_raw_text(input_string)
        
        # Simpan ke Excel di folder uploads
        if hasil:
            df = pd.DataFrame(hasil)
            # Pastikan folder uploads ada
            if not os.path.exists('../uploads'):
                os.makedirs('../uploads')
            df.to_excel("../uploads/data_terstruktur.xlsx", index=False)
        
        # Output JSON untuk ditangkap oleh AJAX PHP
        print(json.dumps(hasil))