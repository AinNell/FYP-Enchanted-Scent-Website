// JavaScript to display favourites when the page loads
window.onload = function() {
    // Get the favourites from localStorage
    let favourites = JSON.parse(localStorage.getItem('favourites')) || [];

    // Get the container where favourites will be displayed
    const favouriteContainer = document.getElementById('favourite-container');

    // Check if there are any favourites
    if (favourites.length > 0) {
        // Loop through each favourite and create a card
        favourites.forEach(fav => {
            const favItem = document.createElement('div');
            favItem.classList.add('favourite-item');
            favItem.innerHTML = `
                <img src="${fav.img}" alt="${fav.name}" class="perfume-img">
                <h3>${fav.name}</h3>
                <p>${fav.description}</p>
                <button class="remove-button" onclick="removeFromFavourites('${fav.name}')">Remove from Favourites</button>
            `;
            favouriteContainer.appendChild(favItem);
        });
    } else {
        favouriteContainer.innerHTML = '<p>No favourites added yet.</p>';
    }
};

// JavaScript function to remove a perfume from favourites
function removeFromFavourites(name) {
    // Get current favourites from localStorage
    let favourites = JSON.parse(localStorage.getItem('favourites')) || [];

    // Filter out the perfume that the user wants to remove
    favourites = favourites.filter(fav => fav.name !== name);

    // Save the updated list of favourites
    localStorage.setItem('favourites', JSON.stringify(favourites));

    // Reload the favourite page to update the list
    window.location.reload();
}
