// Import required modules
import vision from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0";
const { FaceLandmarker, FilesetResolver } = vision;

// Initialize variables
let faceLandmarker;
let enableWebcamButton;
let runningMode = "IMAGE";
let webcamRunning = false;
let opened = 0, closed = 0;
let videoStream;

const filesetResolver = await FilesetResolver.forVisionTasks(
  "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0/wasm"
);
// Create FaceLandmarker instance
faceLandmarker = await FaceLandmarker.createFromOptions(filesetResolver, {
  baseOptions: {
    modelAssetPath: `https://storage.googleapis.com/mediapipe-models/face_landmarker/face_landmarker/float16/1/face_landmarker.task`,
    delegate: "GPU"
  },
  outputFaceBlendshapes: true,
  runningMode,
  numFaces: 1
});
// Get video and canvas elements
const video = document.getElementById("webcam");

// Check if webcam access is supported
function hasGetUserMedia() {
  return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
}

// Check if webcam is supported and add event listener to enable button
if (hasGetUserMedia()) {
  enableWebcamButton = document.getElementById("webcamButton");
  enableWebcamButton.addEventListener("click", enableCam);
} else {
  console.warn("getUserMedia() is not supported by your browser");
}

// Enable webcam and start detection
function enableCam(event) {
  if (!faceLandmarker) {
    console.log("Wait! faceLandmarker not loaded yet.");
    return;
  }
  // Toggle webcam running state
  if (webcamRunning === true) {
    webcamRunning = false;
    enableWebcamButton.innerText = "ENABLE PREDICTIONS";
    stopWebcam();
  } else {
    webcamRunning = true;
    enableWebcamButton.innerText = "DISABLE PREDICTIONS";
    startWebcam();
  }
}

// Start the webcam stream
function startWebcam() {
  // Define webcam stream constraints
  const constraints = {
    video: true
  };
  // Activate webcam stream
  navigator.mediaDevices.getUserMedia(constraints).then(function (stream) {
    videoStream = stream;
    video.srcObject = stream;
    video.addEventListener("loadeddata", predictWebcam);
  });
}

// Stop the webcam stream
function stopWebcam() {
  video.srcObject = null;
  video.removeEventListener("loadeddata", predictWebcam);
  videoStream.getTracks().forEach(track => track.stop());
}

// Initialize variables for webcam prediction
let lastVideoTime = -1;
let results = undefined;

// Function to perform face detection on webcam stream
async function predictWebcam() {
  // Change running mode to video
  if (runningMode === "IMAGE") {
    runningMode = "VIDEO";
    await faceLandmarker.setOptions({ runningMode: runningMode });
  }

  // Get current time
  let nowInMs = Date.now();

  // Detect faces in the video stream
  if (lastVideoTime !== video.currentTime) {
    lastVideoTime = video.currentTime;
    results = faceLandmarker.detectForVideo(video, nowInMs);
  }
  // Calculate attentiveness score
  attentiveness(results.faceBlendshapes);
  // Call the function again to keep predicting when the browser is ready
  if (webcamRunning === true) {
    window.requestAnimationFrame(predictWebcam);
  }
}

// Function to calculate attentiveness score
function attentiveness(blendShapes) {
  let attentivenessScore = 0;
  if (!blendShapes.length) {
    return;
  }
  blendShapes[0].categories.map((shape) => {
    if (
      (shape.displayName || shape.categoryName) == "eyeBlinkLeft" ||
      (shape.displayName || shape.categoryName) == "eyeBlinkRight"
    ) {
      if (shape.score > 0.3) {
        closed++;
      } else {
        opened++;
      }
    }
  });
  attentivenessScore = ((opened / (opened + closed)) * 100).toFixed(2);
  document.getElementById("attentiveness").innerHTML = attentivenessScore + "%";
}
