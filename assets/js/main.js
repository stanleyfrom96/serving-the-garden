// Toggle between two sizes: small and large
const fileItems = document.querySelectorAll(".file-item");

function toggleSize(button) {
  const fileItem = button.closest(".file-item");
  const currentSize = fileItem.getAttribute("data-size");

  if (currentSize === "small") {
    fileItem.setAttribute("data-size", "large");
    fileItem.style.transform = "scale(2)"; // Enlarge the item
  } else {
    fileItem.setAttribute("data-size", "small");
    fileItem.style.transform = "scale(1)"; // Reset to original size
  }
}

// Variable to track the highest z-index
let highestZIndex = 1000;

// Make the file items draggable
interact(".file-item").draggable({
  onstart(event) {
    var target = event.target;

    // Store initial position when dragging starts
    target.setAttribute("data-top", target.style.top || 0);
    target.setAttribute("data-left", target.style.left || 0);

    // Increase the z-index of the dragged item
    target.style.zIndex = highestZIndex + 1;

    // Update the highest z-index for the next item
    highestZIndex++;

    // Bring the dragged item to the front
  },

  onmove(event) {
    var target = event.target;

    // Prevent the item from jumping by using current position correctly
    target.style.top =
      parseFloat(target.getAttribute("data-top")) + event.dy + "px";
    target.style.left =
      parseFloat(target.getAttribute("data-left")) + event.dx + "px";

    // Update the position to persist during drag
    target.setAttribute("data-top", target.style.top);
    target.setAttribute("data-left", target.style.left);
  },

  onend(event) {
    var target = event.target;

    // Reset z-index once dragging ends
    // target.style.zIndex = "";
  },
});
