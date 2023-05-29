// Import required modules
import vision from "https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0";
const { FaceLandmarker, FilesetResolver} = vision;

// Get DOM elements
const demosSection = document.getElementById("demos");

// Initialize variables
let faceLandmarker;
let enableWebcamButton;
let runningMode = "IMAGE";
let webcamRunning = false;
let opened = 0, closed = 0;

// Function to run the demo
async function runDemo() {
    // Initialize filesetResolver
    const filesetResolver = await FilesetResolver.forVisionTasks("https://cdn.jsdelivr.net/npm/@mediapipe/tasks-vision@0.10.0/wasm");

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
    // Show the demos section
    demosSection.classList.remove("invisible");
}
// Run the demo
runDemo();
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
    } else {
        webcamRunning = true;
        enableWebcamButton.innerText = "DISABLE PREDICTIONS";
    }
    // Define webcam stream constraints
    const constraints = {
        video: true
    };
    // Activate webcam stream
    navigator.mediaDevices.getUserMedia(constraints).then(function (stream) {
        video.srcObject = stream;
        video.addEventListener("loadeddata", predictWebcam);
    });
}

// Initialize variables for webcam prediction
let lastVideoTime = -1;
let results = undefined;
// const drawingUtils = new DrawingUtils(canvasCtx);

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
    attentivness(results.faceBlendshapes);
    // Call the function again to keep predicting when the browser is ready
    if (webcamRunning === true) {
        window.requestAnimationFrame(predictWebcam);
    }
}
// Function to calculate attentiveness score
function attentivness(blendShapes) {
    let attentivnessScore = 0;
    if (!blendShapes.length) {
        return;
    }
    blendShapes[0].categories.map((shape) => {
        if ((shape.displayName || shape.categoryName) == "eyeBlinkLeft" || (shape.displayName || shape.categoryName) == "eyeBlinkRight") {
            if (shape.score > 0.3) {
                closed++;
                console.log("Closed: " + closed);
            } else {
                opened++;
                console.log("Opened: " + opened)
            }
        }
    });
    attentivnessScore = ((opened / (opened + closed)) * 100).toFixed(2);
    document.getElementById("attentiveness").innerHTML = attentivnessScore + "%";
}
