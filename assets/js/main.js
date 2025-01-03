// Toggle between two sizes: small and large
const fileItems = document.querySelectorAll(".file-item");

fileItems.forEach((item) => {
  item.addEventListener("click", () => {
    if (item.getAttribute("data-size") === "small") {
      item.setAttribute("data-size", "large");
      item.classList.add("large");
      item.classList.remove("small");
    } else {
      item.setAttribute("data-size", "small");
      item.classList.add("small");
      item.classList.remove("large");
    }
  });
});

// Make the file items draggable
interact(".file-item").draggable({
  onmove(event) {
    var target = event.target;
    target.style.top =
      parseFloat(target.getAttribute("data-top")) + event.dy + "px";
    target.style.left =
      parseFloat(target.getAttribute("data-left")) + event.dx + "px";
    target.setAttribute("data-top", target.style.top);
    target.setAttribute("data-left", target.style.left);
  },
  onstart(event) {
    var target = event.target;
    target.setAttribute("data-top", target.style.top || 0);
    target.setAttribute("data-left", target.style.left || 0);
  },
});
