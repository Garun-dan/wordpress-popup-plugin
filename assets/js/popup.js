// document.addEventListener('DOMContentLoaded', function () {
//     fetch('/wp-json/artistudio/v1/popup', {
//         credentials: 'include',
//         headers: {
//             'X-WP-Nonce': wpPopup.nonce
//         }
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.length > 0) {
//             let popup = document.createElement('div');
//             popup.id = 'popup-container';
//             popup.innerHTML = `
//                 <div class="popup-content">
//                     <span id="popup-close">&times;</span>
//                     ${data[0].content}
//                 </div>
//             `;
//             document.body.appendChild(popup);

//             document.getElementById('popup-close').addEventListener('click', function() {
//                 document.getElementById('popup-container').remove();
//             });
//         }
//     })
//         .catch(error => console.error('Error fetching popup:', error));
    
//     useEffect(() => {
//     fetch('/artistudio/wp-json/artistudio/v1/popup', { credentials: 'include' })
//         .then(response => response.json())
//         .then(data => {
//             console.log("Pop-up Data:", data); // Debugging
//             setPopup({
//                 title: data[0]?.title, // Langsung ambil title
//                 content: data[0]?.content // Ambil content tanpa .rendered
//             });
//         })
//         .catch(error => console.error("Error fetching popup:", error));
// }, []);
    
//     console.log("React Popup Component Mounted");
// });
document.addEventListener('DOMContentLoaded', function () {
    fetch('/artistudio/wp-json/artistudio/v1/popup', {
        credentials: 'include',
        headers: {
            'X-WP-Nonce': wpPopup.nonce
        }
    })
    .then(response => response.json())
    .then(data => {
        if (Array.isArray(data) && data.length > 0) {
            let popup = document.createElement('div');
            popup.id = 'popup-container';
            popup.innerHTML = `
                <div class="popup-content">
                    <span id="popup-close">&times;</span>
                    ${data[0].content.rendered}
                </div>
            `;

            document.body.appendChild(popup);

            document.getElementById('popup-close').addEventListener('click', function() {
                document.getElementById('popup-container').remove();
            });
        }
    })
        .catch(error => console.error('Error fetching popup:', error));
});


