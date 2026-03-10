import threading
import subprocess
import os

# Fungsi untuk menjalankan salah satu robot
def jalankan_modul(nama_file):
    print(f"Memulai: {nama_file}")
    # Menjalankan script .py yang spesifik
    os.system(f"python {nama_file}")

# List modul robot yang ingin dijalankan
modul_robot = ["../spos/jalan_robot.py", "../npp/jalan_robot.py", "../ntp/jalan_robot.py"]

# Membuat thread untuk setiap robot
threads = []
for file in modul_robot:
    t = threading.Thread(target=jalankan_modul, args=(file,))
    threads.append(t)
    t.start()

# Menunggu semua proses selesai
for t in threads:
    t.join()

print("Semua proses robot selesai.")