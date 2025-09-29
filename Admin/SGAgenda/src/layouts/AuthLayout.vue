<template>
    <q-layout view="hHr LpR lFf">
        <main class="">
            <router-link to="/">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>      

            </router-link>

            
            <div class="view">
                <div class="views">
                    <router-view></router-view>

                </div>

                <div>
                    <img 
                        class="auth-img"
                        src="../../public/images/auth-img.png" 
                        alt=""
                    />
                </div>
            </div>
        </main>
    </q-layout>
</template>

<style lang="scss">
    @media (min-width: 1100px) {
        .view {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            
            .views {
                margin: 5rem 0 0 0;
            }

            .auth-img {
                height: 90%;
                border-radius: 10px;
            }
        }
    }      

    @media (max-width: 1250px) {
        .view .views {
            display: flex;
            justify-content: center;
        }
    }
</style>