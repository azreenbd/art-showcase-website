function favouriteUpdate(isFavourite, artwork_id, totalFavourite) {
    document.getElementById("favourite-iframe").addEventListener("load", function(){
        
        if (isFavourite) {
            totalFavourite++;
        
            document.getElementById("favourite-icon-" + artwork_id.toString()).innerHTML = 
            `
            <a title="Favourite" class="btn btn-link py-0 px-1" style="font-size: 1.125rem" onclick="event.preventDefault(); document.getElementById('unfavourite-form-`+artwork_id.toString()+`').submit(); favouriteUpdate(false, `+artwork_id.toString()+`, `+totalFavourite.toString()+`);">
                <i class="fas fa-heart text-danger"></i> ` + totalFavourite.toString() + `
            </a>
            `;
        }
        else {
            if(totalFavourite > 0) { totalFavourite--; }

            document.getElementById("favourite-icon-" + artwork_id.toString()).innerHTML = 
            `
            <a title="Favourite" class="btn btn-link py-0 px-1" style="font-size: 1.125rem" onclick="event.preventDefault(); document.getElementById('favourite-form-`+artwork_id.toString()+`').submit(); favouriteUpdate(true, `+artwork_id.toString()+`, `+totalFavourite.toString()+`);">
                <i class="far fa-heart"></i> ` + totalFavourite.toString() + `
            </a>
            `;
        }
        
    });
}