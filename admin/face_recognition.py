import os
import sys
import json
import cv2
import numpy as np
from datetime import datetime
from deepface import DeepFace

LOG_FILE = "C:/xampp/htdocs/HTTTDN/admin/logs/face_recognition_log.txt"
THRESHOLD = 0.5  # Tăng ngưỡng nhận diện để giảm sai sót
MODEL_NAME = "Facenet"  # Sử dụng mô hình nhẹ hơn để tránh lỗi bộ nhớ

def write_log(message):
    os.makedirs(os.path.dirname(LOG_FILE), exist_ok=True)
    with open(LOG_FILE, "a", encoding="utf-8") as f:
        f.write(f"[{datetime.now()}] {message}\n")

write_log("🚀 Bắt đầu nhận diện khuôn mặt")

if len(sys.argv) < 3:
    write_log("❌ Lỗi: Thiếu tham số đầu vào!")
    print(json.dumps({"status": "error", "message": "Thiếu tham số!"}))
    sys.exit(1)

image_path = sys.argv[1]
profile_path = sys.argv[2]

write_log(f"📂 Ảnh đầu vào: {image_path}")
write_log(f"📂 Ảnh profile: {profile_path}")

if not os.path.exists(image_path) or not os.path.exists(profile_path):
    write_log("❌ Không tìm thấy ảnh!")
    print(json.dumps({"status": "error", "message": "Không tìm thấy ảnh!"}))
    sys.exit(1)

def is_valid_image(img_path):
    img = cv2.imread(img_path)
    return img is not None

if not is_valid_image(image_path) or not is_valid_image(profile_path):
    write_log("❌ Ảnh bị lỗi, không thể mở!")
    print(json.dumps({"status": "error", "message": "Ảnh bị lỗi, không thể mở!"}))
    sys.exit(1)

def detect_face(img_path):
    img = cv2.imread(img_path)
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
    faces = face_cascade.detectMultiScale(gray, scaleFactor=1.1, minNeighbors=5, minSize=(30, 30))
    return len(faces) > 0

if not detect_face(image_path) or not detect_face(profile_path):
    write_log("⚠️ Ảnh không có khuôn mặt hợp lệ!")
    print(json.dumps({"status": "error", "message": "Ảnh không có khuôn mặt!"}))
    sys.exit(1)

def resize_image(img_path, size=(128, 128)):
    img = cv2.imread(img_path)
    img = cv2.resize(img, size)
    cv2.imwrite(img_path, img)
    return img_path  # Trả về đường dẫn ảnh mới

image_path = resize_image(image_path)
profile_path = resize_image(profile_path)

def calculate_brightness(img_path):
    img = cv2.imread(img_path, cv2.IMREAD_GRAYSCALE)
    return np.mean(img)

brightness = calculate_brightness(image_path)
if brightness < 30:
    write_log(f"⚠️ Cảnh báo: Ảnh quá tối (độ sáng: {brightness}), bỏ qua!")
    print(json.dumps({"status": "error", "message": "Ảnh quá tối!"}))
    sys.exit(1)

try:
    verification_result = DeepFace.verify(
        img1_path=profile_path, 
        img2_path=image_path, 
        model_name=MODEL_NAME,
        enforce_detection=False  # Tránh lỗi nếu không tìm thấy khuôn mặt
    )
    
    confidence_score = verification_result["distance"]

    if confidence_score < THRESHOLD:
        write_log(f"✅ Nhận diện thành công! Độ tin cậy: {confidence_score}")
        print(json.dumps({"status": "success", "confidence": confidence_score}))
    else:
        write_log(f"❌ Không khớp với profile! Độ tin cậy: {confidence_score}")
        print(json.dumps({"status": "error", "message": "Không khớp với profile!"}))
        sys.exit(1)

except Exception as e:
    write_log(f"❌ Lỗi khi so sánh khuôn mặt: {str(e)}")
    print(json.dumps({"status": "error", "message": f"Lỗi so sánh khuôn mặt: {str(e)}"}))
    sys.exit(1)

finally:
    os.remove(image_path)  # Xóa ảnh sau khi xử lý
    os.remove(profile_path)
    cv2.destroyAllWindows()