<div id="popup-container" v-if="showPopup">
    <div class="popup-content">{{ popupContent }}</div>
    <button @click="showPopup = false">Close</button>
</div>