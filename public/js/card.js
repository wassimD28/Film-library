function cardOnHover(event) {
    const targetedCard = event.currentTarget;
    console.log('targetedCard:', targetedCard); // Log to verify the element
    const gradient = targetedCard.querySelector(".GRADIENT");
    const movieInfo = targetedCard.querySelector(".INFO");

    console.log('gradient:', gradient); // Log to verify the gradient element
    console.log('movieInfo:', movieInfo); // Log to verify the movie info element

    if (event.type === "mouseover") {
        console.log("mouseover");
        if (gradient && movieInfo) {
            gradient.style.opacity = "0.75";
            movieInfo.style.opacity = "1";
            movieInfo.style.bottom = "10px";
        }
    } else if (event.type === "mouseout") {
        console.log("mouseout");
        if (gradient && movieInfo) {
            gradient.style.opacity = "0";
            movieInfo.style.opacity = "0";
            movieInfo.style.bottom = "-60px";
        }
    }
}
