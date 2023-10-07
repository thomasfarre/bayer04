<template>
    <div>
        <div class="pt-12 pl-12">
            <h1 class="text-4xl font-bold">
                FM23 Compo
            </h1>
        </div>
        <div class="flex flex-col items-center justify-center h-screen">
            <div ref="draggableArea" class="relative w-3/4 h-2/3 border-4 border-black bg-green-300" @mousemove="mouseMove"
                @mouseup="mouseUp">

                <div class="absolute h-full top-0 bottom-0 left-1/2 transform -translate-x-1/2 w-1 bg-white"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-32 h-32 rounded-full border-4 border-white"></div>
                <div class="absolute top-1/2 left-0 transform -translate-y-1/2 w-20 h-48 border-t-4 border-r-4 border-b-4 border-white"></div>
                <div class="absolute top-1/2 right-0 transform -translate-y-1/2 w-20 h-48 border-t-4 border-l-4 border-b-4 border-white"></div>

                <!-- Draggable Role -->
                <div v-for="role in roles" :key="role.id" :style="{ left: role.x + 'px', top: role.y + 'px' }"
                    class="draggableText bg-white p-4 rounded-md w-40" @mousedown="mouseDown(role, $event)">

                    <div class="mb-2 border-b border-gray-800">
                        <span class="text-xl text-bold">
                            {{ role.name }}
                        </span>
                    </div>
                    <div>
                        <div v-for="player in role.players" :key="player.id" class="flex space-x-2">
                            <span>
                                {{ player.name }}
                            </span>
                            <button @click.prevent="confirmDelete(player)">
                                <!-- SVG Icon for delete button -->
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            roles: window.roles, // Ensure roles have x, y properties for positioning
            selectedRole: null,
            offsetX: 0,
            offsetY: 0
        };
    },
    methods: {
        mouseDown(role, event) {
            this.selectedRole = role;

            let rect = event.target.getBoundingClientRect();
            let containerRect = this.$refs.draggableArea.getBoundingClientRect();

            this.offsetX = event.clientX - rect.left + containerRect.left;
            this.offsetY = event.clientY - rect.top + containerRect.top;
        },
        mouseUp() {
            this.selectedRole = null;
        },
        mouseMove(event) {
            if (this.selectedRole) {
                this.selectedRole.x = event.clientX - this.offsetX;
                this.selectedRole.y = event.clientY - this.offsetY;
            }
        },
        confirmDelete(player) {
            // Handle delete confirmation here.
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
