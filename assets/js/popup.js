new Vue({
    el: '#popup-container',
    data: {
        showPopup: false,
        popupContent: ''
    },
    created() {
        fetch('/wp-json/artistudio/v1/popup')
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    this.popupContent = data[0].content;
                    this.showPopup = true;
                }
            });
    }
});