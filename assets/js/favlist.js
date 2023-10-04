function addToFavlist(event)
{
    event.preventDefault();

    const favlistLink = event.currentTarget;
    const link = favlistLink.href;
    // Send an HTTP request with fetch to the URI defined in the href
    try {
        fetch(link)
        // Extract the JSON from the response
            .then(res => res.json())
        // Then update the icon
            .then(data => {
                const favlistIcon = favlistLink.firstElementChild;
                if (data.isInFavlist) {
                    favlistIcon.classList.remove("bi-bookmark-plus"); // Remove the .bi-heart (empty heart) from classes in <i> element
                    favlistIcon.classList.add("bi-bookmark-x-fill"); // Add the .bi-heart-fill (full heart) from classes in <i> element
                } else {
                    favlistIcon.classList.remove("bi-bookmark-x-fill"); // Remove the .bi-heart-fill (full heart) from classes in <i> element
                    favlistIcon.classList.add("bi-bookmark-plus"); // Add the .bi-heart (empty heart) from classes in <i> element
                }
            });
    } catch (err) {
        // eslint-disable-next-line no-console
        console.error(err);
    }
}
