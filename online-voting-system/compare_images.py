import sys
import cv2
from skimage.metrics import structural_similarity as compare_ssim

def compare_images(image1_path, image2_path):
    image1 = cv2.imread(image1_path)
    image2 = cv2.imread(image2_path)

    # Convert to grayscale
    image1_gray = cv2.cvtColor(image1, cv2.COLOR_BGR2GRAY)
    image2_gray = cv2.cvtColor(image2, cv2.COLOR_BGR2GRAY)

    # Use face detector
    face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')

    faces1 = face_cascade.detectMultiScale(image1_gray, 1.1, 4)
    faces2 = face_cascade.detectMultiScale(image2_gray, 1.1, 4)

    if len(faces1) == 0 or len(faces2) == 0:
        print(False)
        return

    # Assume the first detected face is the main face
    x1, y1, w1, h1 = faces1[0]
    x2, y2, w2, h2 = faces2[0]

    # Crop faces
    face1 = image1_gray[y1:y1+h1, x1:x1+w1]
    face2 = image2_gray[y2:y2+h2, x2:x2+w2]

    # Compare faces using SSIM
    score, _ = compare_ssim(face1, face2, full=True)
    print(score > 0.5)

if __name__ == "__main__":
    image1_path = sys.argv[1]
    image2_path = sys.argv[2]
    compare_images(image1_path, image2_path)
