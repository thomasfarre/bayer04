<!-- assets/js/components/Dashboard.vue -->

<template>
  <div>
    <div class="pt-12 pl-12">
      <h1 class="text-4xl font-bold">
        FM23 Compo
      </h1>
    </div>
    <div class="flex flex-col items-center justify-center h-screen">
      <div class="pb-12">
        <form @submit.prevent="addText">
          <input v-model="inputText" type="text" placeholder="Enter text...">
          <button type="submit">Add Player</button>
        </form>
      </div>
      <div ref="draggableArea" class="relative w-3/4 h-2/3 border-4 border-black bg-green-300" @mousemove="mouseMove"
        @mouseup="mouseUp">
        <div class="absolute h-full top-0 bottom-0 left-1/2 transform -translate-x-1/2 w-1 bg-white"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-32 h-32 rounded-full border-4 border-white"></div>
        <div class="absolute top-1/2 left-0 transform -translate-y-1/2 w-20 h-48 border-t-4 border-r-4 border-b-4 border-white"></div>
        <div class="absolute top-1/2 right-0 transform -translate-y-1/2 w-20 h-48 border-t-4 border-l-4 border-b-4 border-white"></div>

        <div v-for="(text, index) in texts" :key="index" :style="{ left: text.x + 'px', top: text.y + 'px' }"
          class="draggableText" @mousedown="mouseDown(text, $event)">
          {{ text.value }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        inputText: '',
        texts: [],
        selectedText: null,
        offsetX: 0,
        offsetY: 0
      };
    },
    methods: {
      addText() {
        this.texts.push({
          value: this.inputText,
          x: 0,
          y: 0
        });
        this.inputText = '';
      },
      mouseDown(text, event) {
        this.selectedText = text;

        let rect = event.target.getBoundingClientRect();
        let containerRect = this.$refs.draggableArea.getBoundingClientRect();

        this.offsetX = event.clientX - rect.left + containerRect.left;
        this.offsetY = event.clientY - rect.top + containerRect.top;
      },
      mouseUp() {
        this.selectedText = null;
      },
      mouseMove(event) {
        if (this.selectedText) {
          this.selectedText.x = event.clientX - this.offsetX;
          this.selectedText.y = event.clientY - this.offsetY;
        }
      }
    }
  };
</script>


<style scoped>
  .draggableText {
    position: absolute;
    padding: 6px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
    cursor: move;
  }

  #draggableArea {
    user-select: none;
  }

  /* ... Other CSS ... */
</style>
