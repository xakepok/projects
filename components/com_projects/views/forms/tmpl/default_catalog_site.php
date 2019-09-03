<?php
defined('_JEXEC') or die;
?>

<div><h5><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_TITLE');?></h5></div>
<div>
    <form action="#">
        <div class="form-group row">
            <label for="title" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_ORG_NAME');?></label>
            <div class="col-md-10">
                <input type="text" name="title" id="title" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="diploma" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_ORG_NAME_DIPLOMA');?></label>
            <div class="col-md-10">
                <input type="text" name="diploma" id="diploma" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="title_ru" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_ORG_NAME_RU');?></label>
            <div class="col-md-10">
                <input type="text" name="title_ru" id="title_ru" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="title_en" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_ORG_NAME_EN');?></label>
            <div class="col-md-10">
                <input type="text" name="title_en" id="title_en" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="alpha_ru" class="col-md-4 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_ORG_NAME_RU_ALPHA');?></label>
            <div class="col-md-2 text-left">
                <select name="alpha_ru" id="alpha_ru">
                    <option value="А">А</option>
                    <option value="Б">Б</option>
                    <option value="В">В</option>
                    <option value="Г">Г</option>
                    <option value="Д">Д</option>
                    <option value="Е">Е</option>
                    <option value="Ё">Ё</option>
                    <option value="Ж">Ж</option>
                    <option value="З">З</option>
                    <option value="И">И</option>
                    <option value="К">К</option>
                    <option value="Л">Л</option>
                    <option value="М">М</option>
                    <option value="Н">Н</option>
                    <option value="О">О</option>
                    <option value="П">П</option>
                    <option value="Р">Р</option>
                    <option value="С">С</option>
                    <option value="Т">Т</option>
                    <option value="У">У</option>
                    <option value="Ф">Ф</option>
                    <option value="Х">Х</option>
                    <option value="Ц">Ц</option>
                    <option value="Ч">Ч</option>
                    <option value="Ш">Ш</option>
                    <option value="Щ">Щ</option>
                    <option value="Э">Э</option>
                    <option value="Ю">Ю</option>
                    <option value="Я">Я</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                </select>
            </div>
            <label for="alpha_en" class="col-md-4 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_ORG_NAME_EN_ALPHA');?></label>
            <div class="col-md-2 text-left">
                <select name="alpha_en" id="alpha_en">
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                    <option value="F">F</option>
                    <option value="G">G</option>
                    <option value="H">H</option>
                    <option value="I">I</option>
                    <option value="J">J</option>
                    <option value="K">K</option>
                    <option value="L">L</option>
                    <option value="M">M</option>
                    <option value="N">N</option>
                    <option value="O">O</option>
                    <option value="P">P</option>
                    <option value="Q">Q</option>
                    <option value="R">R</option>
                    <option value="S">S</option>
                    <option value="T">T</option>
                    <option value="U">U</option>
                    <option value="V">V</option>
                    <option value="W">W</option>
                    <option value="X">X</option>
                    <option value="Y">Y</option>
                    <option value="Z">Z</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="title_ru_full" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_ORG_NAME_RU_FULL');?></label>
            <div class="col-md-10">
                <input type="text" name="title_ru_full" id="title_ru_full" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="title_en_full" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_ORG_NAME_EN_FULL');?></label>
            <div class="col-md-10">
                <input type="text" name="title_en_full" id="title_en_full" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="title_en_full" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_ORG_ADDR_RU_FULL');?></label>
            <div class="col-md-10">
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="index_ru"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_INDEX_RU');?></label>
                        <input type="text" name="index_ru" id="index_ru" class="form-control">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="country_ru"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_COUNTRY_RU');?></label>
                        <input type="text" name="country_ru" id="country_ru" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="city_ru"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_TOWN_RU');?></label>
                        <input type="text" name="city_ru" id="city_ru" class="form-control">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="region_ru"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_REGION_RU');?></label>
                        <input type="text" name="region_ru" id="region_ru" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="district_ru"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_DISTRICT_RU');?></label>
                        <input type="text" name="district_ru" id="district_ru" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="street_ru"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_STREET_RU');?></label>
                        <input type="text" name="street_ru" id="street_ru" class="form-control">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="house_ru"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_HOUSE_RU');?></label>
                        <input type="text" name="house_ru" id="house_ru" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="title_en_full" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_ORG_ADDR_EN_FULL');?></label>
            <div class="col-md-10">
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="index_en"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_INDEX_EN');?></label>
                        <input type="text" name="index_en" id="index_en" class="form-control">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="country_en"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_COUNTRY_EN');?></label>
                        <input type="text" name="country_en" id="country_en" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="city_en"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_TOWN_EN');?></label>
                        <input type="text" name="city_en" id="city_en" class="form-control">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="region_en"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_REGION_EN');?></label>
                        <input type="text" name="region_en" id="region_en" class="form-control">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="district_en"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_DISTRICT_EN');?></label>
                        <input type="text" name="district_en" id="district_en" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="street_en"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_STREET_EN');?></label>
                        <input type="text" name="street_en" id="street_en" class="form-control">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="house_en"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_HOUSE_EN');?></label>
                        <input type="text" name="house_en" id="house_en" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="phone_country" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_PHONE');?></label>
            <div class="col-md-10">
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="phone_country"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_PHONE_COUNTRY');?></label>
                        <input type="text" name="phone_country" id="phone_country" class="form-control">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="phone_city"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_PHONE_CITY');?></label>
                        <input type="text" name="phone_city" id="phone_city" class="form-control">
                    </div>
                    <div class="form-group col-md-8">
                        <label for="phone_num"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_PHONE_NUM');?></label>
                        <input type="text" name="phone_num" id="phone_num" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_EMAIL');?></label>
            <div class="col-md-3">
                <input type="text" name="email" id="email" class="form-control">
            </div>
            <label for="site" class="col-md-1 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_SITE');?></label>
            <div class="col-md-6">
                <input type="url" name="site" id="site" class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="text_ru" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_TEXT_RU');?></label>
            <div class="col-md-4">
                <textarea name="text_ru" class="form-control" rows="5"></textarea>
            </div>
            <label for="text_en" class="col-md-2 col-form-label"><?php echo JText::sprintf('COM_PROJECTS_FORM_SITE_TEXT_EN');?></label>
            <div class="col-md-4">
                <textarea name="text_en" class="form-control" rows="5"></textarea>
            </div>
        </div>
    </form>
</div>