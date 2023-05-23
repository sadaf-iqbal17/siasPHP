let video, src, dst, gray, cap, faces, eyes, eyeClassifier, utils;
const FPS = 24;
let processing = false;

function startProcessing() {
    if (processing) return;
    processing = true;
    video = document.getElementById("cam_input");
    src = new cv.Mat(video.height, video.width, cv.CV_8UC4);
    dst = new cv.Mat(video.height, video.width, cv.CV_8UC1);
    gray = new cv.Mat();
    cap = new cv.VideoCapture("cam_input");
    faces = new cv.RectVector();
    eyes = new cv.RectVector();
    eyeClassifier = new cv.CascadeClassifier();
    utils = new Utils('errorMessage');
    let eyeCascadeFile = 'haarcascade_eye.xml';

    utils.createFileFromUrl(eyeCascadeFile, eyeCascadeFile, () => {
        eyeClassifier.load(eyeCascadeFile);
    });

    function processVideo() {
        let begin = Date.now();
        cap.read(src);
        src.copyTo(dst);
        cv.cvtColor(dst, gray, cv.COLOR_RGBA2GRAY, 0);

        let scaleFactor = 1.1;
        let minNeighbors = 3;
        let flags = 0;
        let minSize = new cv.Size(30, 30);

        eyes.delete(); // Delete previous eyes data

        try {
            eyes = new cv.RectVector(); // Create new RectVector for eyes
            eyeClassifier.detectMultiScale(gray, eyes, scaleFactor, minNeighbors, flags, minSize);
        } catch (err) {
            console.log(err);
        }

        for (let i = 0; i < eyes.size(); ++i) {
            let eye = eyes.get(i);
            let eyePoint1 = new cv.Point(eye.x, eye.y);
            let eyePoint2 = new cv.Point(eye.x + eye.width, eye.y + eye.height);
            cv.rectangle(dst, eyePoint1, eyePoint2, [0, 255, 0, 255]);

            let eyeROI = gray.roi(eye);

            // Calculate iris ROI
            let irisROI = new cv.Mat();
            let irisRadius = Math.floor(eye.width / 5);
            let irisCenterX = Math.floor(eye.x + eye.width / 2);
            let irisCenterY = Math.floor(eye.y + eye.height / 2);
            let irisPoint = new cv.Point(irisCenterX, irisCenterY);
            cv.circle(eyeROI, irisPoint, irisRadius, [255, 255, 255, 255], 2);

            let irisAvgIntensity = cv.mean(eyeROI);

            // Check if iris is detected to determine eye state
            let eyeState = irisAvgIntensity[0] > 0 ? "Open" : "Closed";
            let stateTextPoint = new cv.Point(eye.x, eye.y - 10);
            cv.putText(dst, eyeState, stateTextPoint, cv.FONT_HERSHEY_SIMPLEX, 0.9, [255, 255, 255, 255]);

            // Clean up
            eyeROI.delete();
            irisROI.delete();
        }

        cv.imshow("canvas_output", dst);

        // Schedule next frame
        let delay = 1000 / FPS - (Date.now() - begin);
        setTimeout(processVideo, delay);
    }

    // Start camera and processing
    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
        .then(function(stream) {
            video.srcObject = stream;
            video.play();
            setTimeout(processVideo, 0);
        })
        .catch(function(err) {
            console.log("An error occurred! " + err);
        });
}

function stopProcessing() {
    processing = false;
    cap.delete();
    src.delete();
    dst.delete();
    gray.delete();
    faces.delete();
    eyes.delete();
    eyeClassifier.delete();
    utils.delete();
}

document.getElementById("startButton").addEventListener("click", startProcessing);
document.getElementById("stopButton").addEventListener("click", stopProcessing);

function openCvReady() {
    cv['onRuntimeInitialized'] = () => {
        // OpenCV is ready
    };
}


// function openCvReady() {
//     cv['onRuntimeInitialized'] = () => {
//       let video = document.getElementById("cam_input");
//       navigator.mediaDevices.getUserMedia({ video: true, audio: false })
//         .then(function(stream) {
//           video.srcObject = stream;
//           video.play();
//         })
//         .catch(function(err) {
//           console.log("An error occurred! " + err);
//         });
  
//       let src = new cv.Mat(video.height, video.width, cv.CV_8UC4);
//       let dst = new cv.Mat(video.height, video.width, cv.CV_8UC1);
//       let gray = new cv.Mat();
//       let cap = new cv.VideoCapture(cam_input);
//       let faces = new cv.RectVector();
//       let eyes = new cv.RectVector();
//       let eyeClassifier = new cv.CascadeClassifier();
//       let utils = new Utils('errorMessage');
//       let eyeCascadeFile = 'haarcascade_eye.xml';
  
//       utils.createFileFromUrl(eyeCascadeFile, eyeCascadeFile, () => {
//         eyeClassifier.load(eyeCascadeFile);
//       });
  
//       const FPS = 24;
  
//       function processVideo() {
//         let begin = Date.now();
//         cap.read(src);
//         src.copyTo(dst);
//         cv.cvtColor(dst, gray, cv.COLOR_RGBA2GRAY, 0);
  
//         let scaleFactor = 1.1;
//         let minNeighbors = 3;
//         let flags = 0;
//         let minSize = new cv.Size(30, 30);
  
//         eyes.delete(); // Delete previous eyes data
  
//         try {
//           eyes = new cv.RectVector(); // Create new RectVector for eyes
//           eyeClassifier.detectMultiScale(gray, eyes, scaleFactor, minNeighbors, flags, minSize);
//         } catch (err) {
//           console.log(err);
//         }
  
//         for (let i = 0; i < eyes.size(); ++i) {
//           let eye = eyes.get(i);
//           let eyePoint1 = new cv.Point(eye.x, eye.y);
//           let eyePoint2 = new cv.Point(eye.x + eye.width, eye.y + eye.height);
//           cv.rectangle(dst, eyePoint1, eyePoint2, [0, 255, 0, 255]);
  
//           let eyeROI = gray.roi(eye);
          
//           // Calculate iris ROI
//           let irisROI = new cv.Mat();
//           let irisRadius = Math.floor(eye.width / 5);
//           let irisCenterX = Math.floor(eye.x + eye.width / 2);
//           let irisCenterY = Math.floor(eye.y + eye.height / 2);
//           let irisPoint = new cv.Point(irisCenterX, irisCenterY);
//           cv.circle(eyeROI, irisPoint, irisRadius, [255, 255, 255, 255], 2);
  
//           let irisAvgIntensity = cv.mean(eyeROI);
  
//           // Check if iris is detected to determine eye state
//           let eyeState = irisAvgIntensity[0] > 0 ? "Open" : "Closed";
//           let stateTextPoint = new cv.Point(eye.x, eye.y - 10);
//           cv.putText(dst, eyeState, stateTextPoint, cv.FONT_HERSHEY_SIMPLEX, 0.9, [255, 255, 255, 255]);
  
//           // Clean up
//           eyeROI.delete();
//           irisROI.delete();
//         }
  
//         cv.imshow("canvas_output", dst);
  
//         // Schedule next frame
//         let delay = 1000 / FPS - (Date.now() - begin);
//         setTimeout(processVideo, delay);
//       }
  
//       // Schedule first frame
//       setTimeout(processVideo, 0);
//     };
//   }