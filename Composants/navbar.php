<header class="flex flex-row items-center justify-center p-4 w-full">
    <a href="<?php echo Pages::FeedPage ?>" class="mr-40"><img class="h-8" alt="logo"
            src="../images/logo-gradient.png"></img></a>
    <form class="mr-auto my-0" method="get" action="<?php echo Pages::FeedPage ?>">
        <div class="flex flex-row w-50 bg-gray-100 p-2 px-4 rounded-xl">
            <input name="Search" type="search" id="default-search" class="w-full bg-transparent outline-none"
                placeholder="Filtrer par utilisateur..." required>
            <button type="submit"><svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                </svg>
            </button>
        </div>
    </form>
    <div class="w-30">
        <?php if (!checkUser(session_id())) {
            echo '<a href="' . Pages::LoginPage . '" class="whitespace-nowrap transition duration-150 ease-in-out text-orange-400 hover:text-white border border-orange-400 hover:bg-orange-400 focus:ring-4 focus:outline-none focus:ring-orange-400 font-bold rounded-lg px-5 py-2 cursor-pointer">S`inscrire/Se connecter</a>';
        } else { ?>
            <a href="<?php echo Pages::ProfilePage ?>"
                class="whitespace-nowrap transition duration-150 ease-in-out text-orange-400 hover:text-white border border-orange-400 hover:bg-orange-400 focus:ring-4 focus:outline-none focus:ring-orange-400 font-bold rounded-lg px-5 py-2 cursor-pointer">Mon
                compte</a>
        <?php } ?>
    </div>
</header>