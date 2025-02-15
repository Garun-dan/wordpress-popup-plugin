document.addEventListener("DOMContentLoaded", async function () {
    try {
        const response = await fetch('/artistudio/wp-json/artistudio/v1/popup', {
            credentials: 'include',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        if (Array.isArray(data) && data.length > 0) {
            showPopup(data[0]);
            console.log(data[0]);
        } else {
            console.error("Popup data kosong atau tidak dalam format array.");
        }
    } catch (error) {
        console.error("Error fetching popup:", error);
    }
});

function showPopup(popupData) {
    console.log("Popup Data di showPopup:", popupData);

    // Cek apakah struktur data benar
    console.log("Title:", popupData.title);
    console.log("Content:", popupData.content);

    // const title = popupData.title && popupData.title.rendered ? popupData.title.rendered : "Tanpa Judul";
    // const content = popupData.content && popupData.content.rendered ? popupData.content.rendered : "Konten tidak tersedia.";

    // console.log("Processed Title:", title);
    // console.log("Processed Content:", content);

    const popup = document.createElement("div");
    popup.classList.add("popup");
    popup.innerHTML = `
        <div class="popup-content">
            <h2>${popupData.title}</h2>
            <div id="popupContent"></div>
            <button id="closePopup">Tutup</button>
        </div>
    `;

    document.body.appendChild(popup);

    document.getElementById("popupContent").innerHTML = popupData.content;

    document.getElementById("closePopup").addEventListener("click", function () {
        popup.remove();
    });
}


// function showPopup(popupData) {
//     const title = popupData.title?.rendered || "Tanpa Judul";
//     const content = popupData.content?.rendered || "Konten tidak tersedia.";

//     const popup = document.createElement("div");
//     popup.classList.add("popup");
//     // popup.innerHTML = `
//     //     <div class="popup-content">
//     //         <h2>${title}</h2>
//     //         <p>${content}</p>
//     //         <button id="closePopup">Tutup</button>
//     //     </div>
//     // `;
//     popup.innerHTML = `
//         <div class="popup-content">
//             <h2>${title}</h2>
//             <div id="popupContent"></div> 
//             <button id="closePopup">Tutup</button>
//         </div>
//     `;

//     document.body.appendChild(popup);

//     document.getElementById("popupContent").innerHTML = content;

//     document.getElementById("closePopup").addEventListener("click", function () {
//         popup.remove();
//     });

//     // const closeBtn = document.getElementById("closePopup");
//     // if (closeBtn) {
//     //     closeBtn.addEventListener("click", function () {
//     //         popup.remove();
//     //     });
//     // }
// }
