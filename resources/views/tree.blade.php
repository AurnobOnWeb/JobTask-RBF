<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merkle Tree Visualization</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Additional CSS for connectors */
        .connector {
            width: 2px;
            height: 20px;
            background-color: black;
            position: absolute;
            bottom: -20px;
            /* Adjusted to match the height of the nodes */
            left: 50%;
            transform: translateX(-50%);
            z-index: -1;
        }
    </style>
    </style>
</head>

<body>
    <div class="flex justify-center items-center flex-col h-screen ">
        <div class="w-[400px] mb-10">
            <form action="{{ route('tree.genarate') }}" method="POST">
                @csrf
                <label for="insert Number" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d=" m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="number" name="treeNumber"
                        class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-3xl bg-[gray-50] focus:ring-[#797DFCcc]  focus:border-[#797DFC]  outline-none"
                        placeholder="Enter Number" required />
                    <button type="submit"
                        class="text-white absolute end-2.5 bottom-2.5 bg-[#797DFC] hover:bg-[#797DFCcc] focus:ring-4 focus:outline-none focus:ring-[#797DFCcc] font-medium rounded-3xl text-sm px-4 py-2">Construct
                        Tree</button>
                </div>
            </form>
        </div>
        <h1>Genarated Tree Visualization
            (Example : Default Construct
            Tree of 4 Note: max-26 for letter )
        </h1>

        @php
            $levels = [];
            $levels[] = $data;
        @endphp



        @while (count($levels[0]) > 1)
            <div class="flex  mt-5 gap-2 relative"> <!-- Add relative positioning for connectors -->
                @foreach ($levels[0] as $index => $node)
                    <div class="p-3 border border-black">{{ $node }}</div>
                @endforeach
            </div>
            @php
                $nextLevel = [];
                for ($i = 0; $i < count($levels[0]); $i += 2) {
                    $node1 = $levels[0][$i];
                    $node2 = isset($levels[0][$i + 1]) ? $levels[0][$i + 1] : '';
                    $nextLevel[] = $node1 . $node2;
                }
                $levels = [$nextLevel];
            @endphp
        @endwhile
        <div class="p-3 border border-black mt-5">Root - {{ $rootValue }}</div>

    </div>
</body>

</html>
