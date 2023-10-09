<template>
    <div>
        <div class="pl-12">
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
                <div v-for="role in roles" :key="role.id"
                    class="absolute bg-white p-4 rounded-md w-40" :style="{ left: role.x + 'px', top: role.y + 'px' }">

                    <div class="mb-2 border-b border-gray-800 cursor-move" @mousedown="mouseDown(role, $event)">
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
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
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

            this.offsetY = event.clientY - rect.top + containerRect.top;
            this.offsetX = event.clientX - rect.left + containerRect.left;
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
        // Handle delete confirmation here.
        async confirmDelete(player) {
            if (window.confirm(`Are you sure you want to delete ${player.name}?`)) {
                try {
                    let response = await fetch(`/player/${player.id}`, {
                        method: 'POST',
                    });
                    if (response.ok) {
                        alert('Player deleted!');

                        // Find the role that contains the player
                        const role = this.roles.find(r => r.players.some(p => p.id === player.id));

                        // If the role is found, find and remove the player from the role's players array
                        if (role) {
                            const playerIndex = role.players.findIndex(p => p.id === player.id);
                            if (playerIndex !== -1) {
                                role.players.splice(playerIndex, 1);
                            }
                        }
                    } else {
                        let responseData = await response.json();
                        alert(`Failed to delete player: ${responseData.message}`);
                    }
                } catch (error) {
                    // Debug: Log the error
                    console.log('Fetch error: ', error);
                    alert('An error occurred. Please try again.');
                }
            }
        }
    }
};
</script>


<style scoped>


  #draggableArea {
    user-select: none;
  }

  /* ... Other CSS ... */
</style>
