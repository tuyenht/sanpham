<div class="sidebar-tabs">
    <ul class="nav">
        <li class="nav-item <?= $activeTab == 'orders' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= generateUrl("orders"); ?>">
                <div class="icon">
                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><title>basket_line</title>
                        <g id="basket_line" fill='none' fill-rule='nonzero'>
                            <path d='M24 0v24H0V0zM12.593 23.258l-.011.002-.071.035-.02.004-.014-.004-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01-.017.428.005.02.01.013.104.074.015.004.012-.004.104-.074.012-.016.004-.017-.017-.427c-.002-.01-.009-.017-.017-.018m.265-.113-.013.002-.185.093-.01.01-.003.011.018.43.005.012.008.007.201.093c.012.004.023 0 .029-.008l.004-.014-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014-.034.614c0 .012.007.02.017.024l.015-.002.201-.093.01-.008.004-.011.017-.43-.003-.012-.01-.01z'/>
                            <path fill='#333333'
                                  d='M10.464 3.282a2 2 0 0 1 2.964-.12l.108.12L17.469 8h2.985a1.49 1.49 0 0 1 1.484 1.655l-.092.766-.1.74-.082.554-.095.595-.108.625-.122.648-.136.661c-.072.333-.149.667-.232.999a21.018 21.018 0 0 1-.832 2.583l-.221.54-.214.488-.202.434-.094.194-.249.49c-.32.61-.924.97-1.563 1.022l-.16.006H6.555a1.929 1.929 0 0 1-1.71-1.008l-.232-.45-.18-.37a20.09 20.09 0 0 1-.095-.205l-.2-.449a21.536 21.536 0 0 1-1.108-3.276 32.366 32.366 0 0 1-.156-.654l-.142-.648-.127-.634-.112-.613-.1-.587-.087-.554-.074-.513-.09-.683-.066-.556a39.802 39.802 0 0 1-.017-.153 1.488 1.488 0 0 1 1.348-1.64L3.543 8h2.989zM19.883 10H4.113l.062.484.076.548.092.604.108.648c.04.222.081.45.126.682l.143.707c.076.358.16.722.25 1.084a19.532 19.532 0 0 0 1 2.957l.196.441.182.384.163.323.072.138H17.41l.154-.303.177-.365.194-.423c.367-.825.787-1.918 1.095-3.152.125-.5.236-1.008.335-1.507l.14-.738.12-.709.053-.339.095-.637.077-.57zm-9.897 2.836.5 3a1 1 0 0 1-1.972.328l-.5-3a1 1 0 1 1 1.972-.328M15.048 12l.116.013A1 1 0 0 1 16 13.048l-.013.116-.5 3a1 1 0 0 1-1.985-.212l.013-.116.5-3A1 1 0 0 1 15.048 12M12 4.562 9.135 8h5.73z'/>
                        </g>
                    </svg>
                </div>
                <?= trans("orders"); ?>
            </a>
        </li>
        <?php if ($generalSettings->bidding_system == 1): ?>
            <li class="nav-item <?= $activeTab == 'quote_requests' ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= generateUrl("quote_requests"); ?>">
                    <div class="icon">
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><title>tag_line</title>
                            <g id="tag_line" fill='none' fill-rule='evenodd'>
                                <path d='M24 0v24H0V0zM12.593 23.258l-.011.002-.071.035-.02.004-.014-.004-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01-.017.428.005.02.01.013.104.074.015.004.012-.004.104-.074.012-.016.004-.017-.017-.427c-.002-.01-.009-.017-.017-.018m.265-.113-.013.002-.185.093-.01.01-.003.011.018.43.005.012.008.007.201.093c.012.004.023 0 .029-.008l.004-.014-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014-.034.614c0 .012.007.02.017.024l.015-.002.201-.093.01-.008.004-.011.017-.43-.003-.012-.01-.01z'/>
                                <path fill='#333333' d='M10.537 2.164a3 3 0 0 1 2.244.727l.15.14 7.822 7.823a3 3 0 0 1 .135 4.098l-.135.144-5.657 5.657a3 3 0 0 1-4.098.135l-.144-.135L3.03 12.93a3 3 0 0 1-.878-2.188l.011-.205.472-5.185a3 3 0 0 1 2.537-2.695l.179-.021zm.308 1.989-.127.003-5.185.472a1 1 0 0 0-.888.787l-.017.118-.472 5.185a1 1 0 0 0 .206.703l.083.095 7.823 7.823a1 1 0 0 0 1.32.083l.094-.083 5.657-5.657a1 1 0 0 0 .083-1.32l-.083-.094-7.823-7.823a1 1 0 0 0-.671-.292M7.317 7.318a3 3 0 1 1 4.243 4.243 3 3 0 0 1-4.243-4.243m2.829 1.414a1 1 0 1 0-1.415 1.414 1 1 0 0 0 1.415-1.414'/>
                            </g>
                        </svg>
                    </div>
                    <?= trans("quote_requests"); ?>
                </a>
            </li>
        <?php endif;
        if ($generalSettings->digital_products_system == 1): ?>
            <li class="nav-item <?= $activeTab == 'downloads' ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= generateUrl("downloads"); ?>">
                    <div class="icon">
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><title>download_3_line</title>
                            <g id="download_3_line" fill='none' fill-rule='evenodd'>
                                <path d='M24 0v24H0V0h24ZM12.593 23.258l-.011.002-.071.035-.02.004-.014-.004-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01-.017.428.005.02.01.013.104.074.015.004.012-.004.104-.074.012-.016.004-.017-.017-.427c-.002-.01-.009-.017-.017-.018Zm.265-.113-.013.002-.185.093-.01.01-.003.011.018.43.005.012.008.007.201.093c.012.004.023 0 .029-.008l.004-.014-.034-.614c-.003-.012-.01-.02-.02-.022Zm-.715.002a.023.023 0 0 0-.027.006l-.006.014-.034.614c0 .012.007.02.017.024l.015-.002.201-.093.01-.008.004-.011.017-.43-.003-.012-.01-.01-.184-.092Z'/>
                                <path fill='#333333' d='M11.5 4a4.502 4.502 0 0 1 4.344 3.32c.104.383.421.67.811.733A4.001 4.001 0 0 1 18 15.466a1 1 0 1 0 1.002 1.73 6.002 6.002 0 0 0-1.415-10.985A6.502 6.502 0 0 0 5 8.417a5 5 0 0 0 .124 9.22 1 1 0 0 0 .75-1.854 3.001 3.001 0 0 1 .394-5.692A1.01 1.01 0 0 0 7.027 9C6.74 6.38 8.851 4 11.5 4Zm.5 7a1 1 0 0 1 1 1v6.584l1.293-1.292a1 1 0 0 1 1.414 1.416l-2.824 2.819c-.253.252-.5.473-.883.473-.384 0-.63-.22-.883-.473l-2.824-2.82a1 1 0 0 1 1.414-1.415L11 18.584V12a1 1 0 0 1 1-1Z'/>
                            </g>
                        </svg>
                    </div>
                    <?= trans("downloads"); ?>
                </a>
            </li>
        <?php endif;
        if ($generalSettings->refund_system == 1):?>
            <li class="nav-item <?= $activeTab == 'refund_requests' ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= generateUrl("refund_requests"); ?>">
                    <div class="icon">
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'><title>card_refund_line</title>
                            <g id="card_refund_line" fill='none' fill-rule='evenodd'>
                                <path d='M24 0v24H0V0zM12.594 23.258l-.012.002-.071.035-.02.004-.014-.004-.071-.036c-.01-.003-.019 0-.024.006l-.004.01-.017.428.005.02.01.013.104.074.015.004.012-.004.104-.074.012-.016.004-.017-.017-.427c-.002-.01-.009-.017-.016-.018m.264-.113-.014.002-.184.093-.01.01-.003.011.018.43.005.012.008.008.201.092c.012.004.023 0 .029-.008l.004-.014-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014-.034.614c0 .012.007.02.017.024l.015-.002.201-.093.01-.008.003-.011.018-.43-.003-.012-.01-.01z'/>
                                <path fill='#333333' d='M19 4a3 3 0 0 1 2.995 2.824L22 7v10a3 3 0 0 1-2.824 2.995L19 20H5a3 3 0 0 1-2.995-2.824L2 17V7a3 3 0 0 1 2.824-2.995L5 4zm1 6H4v7a1 1 0 0 0 .883.993L5 18h14a1 1 0 0 0 .993-.883L20 17zM9.293 11.464a1 1 0 0 1 1.497 1.32l-.083.095-.121.121H15a1 1 0 0 1 .117 1.993L15 15h-4.414l.12.121a1 1 0 0 1-1.32 1.498l-.093-.084-1.829-1.828a1 1 0 0 1-.083-1.32l.083-.094zM19 6H5a1 1 0 0 0-1 1v1h16V7a1 1 0 0 0-1-1'/>
                            </g>
                        </svg>
                    </div>
                    <?= trans("refund_requests"); ?>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</div>