<?php if (!empty($countries)): ?>
    <div class="row">
        <?php if ($generalSettings->single_country_mode != 1): ?>
            <div class="col-12 <?= !empty($isFullWidth) ? 'col-lg-12' : 'col-lg-4'; ?> m-b-15">
                <select id="select_countries" name="country_id" class="select2 form-control <?= empty($isLocationOptional) ? 'select2-req' : ''; ?>" onchange="getStates(this.value);" <?= !empty($isLocationOptional) ? '' : 'required'; ?>>
                    <option value=""><?= trans('country'); ?></option>
                    <?php foreach ($countries as $item):
                        if ($item->status == 1):
                            if (!empty($countryId)): ?>
                                <option value="<?= $item->id; ?>" <?= $item->id == $countryId ? 'selected' : ''; ?>><?= esc($item->name); ?></option>
                            <?php else: ?>
                                <option value="<?= $item->id; ?>"><?= esc($item->name); ?></option>
                            <?php endif;
                        endif;
                    endforeach; ?>
                </select>
            </div>
        <?php else: ?>
            <input type="hidden" name="country_id" value="<?= $generalSettings->single_country_id; ?>">
            <?php $countryId = $generalSettings->single_country_id;
            $states = getStatesByCountry($countryId);
        endif; ?>
        <div id="get_states_container" class="col-12 <?= !empty($isFullWidth) ? 'col-lg-12' : 'col-lg-4'; ?> m-b-15 <?= (!empty($countryId)) ? '' : 'display-none'; ?>">
            <select id="select_states" name="state_id" class="select2 form-control" onchange="getCities(this.value);">
                <option value=""><?= trans('state'); ?></option>
                <?php if (!empty($states)):
                    foreach ($states as $item):
                        if (!empty($stateId)): ?>
                            <option value="<?= $item->id; ?>" <?= $item->id == $stateId ? 'selected' : ''; ?>><?= esc($item->name); ?></option>
                        <?php else: ?>
                            <option value="<?= $item->id; ?>"><?= esc($item->name); ?></option>
                        <?php endif;
                    endforeach;
                endif; ?>
            </select>
        </div>
        <div id="get_cities_container" class="col-12 <?= !empty($isFullWidth) ? 'col-lg-12' : 'col-lg-4'; ?> m-b-15 <?= (!empty($cities)) ? '' : 'display-none'; ?>">
            <select id="select_cities" name="city_id" class="select2 form-control">
                <option value=""><?= trans('city'); ?></option>
                <?php if (!empty($cities)):
                    foreach ($cities as $item):
                        if (!empty($cityId)): ?>
                            <option value="<?= $item->id; ?>" <?= $item->id == $cityId ? 'selected' : ''; ?>><?= esc($item->name); ?></option>
                        <?php else: ?>
                            <option value="<?= $item->id; ?>"><?= esc($item->name); ?></option>
                        <?php endif;
                    endforeach;
                endif; ?>
            </select>
        </div>
    </div>
<?php endif; ?>