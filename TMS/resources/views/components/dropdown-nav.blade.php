<a {{ $attributes->merge(['class' => 'block 
                                    w-[180px] px-4 py-2 ml-2
                                    text-zinc-700
                                    text-left text-xs font-medium 
                                    leading-5 text-zinc-700 
                                    hover:bg-blue-100 
                                    hover:text-blue-950 
                                    hover:font-bold 
                                    hover:rounded-full 
                                    cursor-pointer
                                    focus:outline-none 
                                    focus:bg-blue-100 
                                    transition duration-150 ease-in-out']) }}>{{ $slot }}</a>
