<div class="off_canvas_menu off_canvas_menu_left">
    <div id="off_canvas_trigger_menu" class="off_canvas_trigger off_canvas_trigger_left"></div>
    <div class="off_canvas_menu_section">
        <div class="off_canvas_menu_title">
            <h1>eFlorist Manager Panel</h1>
        </div>
    </div>
    <div class="off_canvas_menu_section">
        <div class="off_canvas_menu_section_title">
            <h2>Manage Page</h2>
        </div>
        [[manage_menu_page:object=`entity_web_page`:template_name=`chunk_manager_menu_item`]]
    </div><!-- #off_canvas_menu_section_main_menu -->
    <div class="off_canvas_menu_section">
        <div class="off_canvas_menu_section_title">
            <h2>Manage Product</h2>
        </div>
        <div class="off_canvas_menu_item">
            <a href="[[*control_panel]]/product/list_category">Category</a>
        </div>
        <div class="off_canvas_menu_item">
            <a href="[[*control_panel]]/product/list_product">Product</a>
        </div>
    </div>
    <div class="off_canvas_menu_section">
        <div class="off_canvas_menu_item">
            <a href="/logout">Logout</a>
        </div>
    </div>
</div><!-- #off_canvas_menu -->
<div class="off_canvas_menu off_canvas_menu_right">
    <div id="off_canvas_trigger_search" class="off_canvas_trigger off_canvas_trigger_right"></div>
    <div class="off_canvas_menu_section">
        <form action="[[*control_panel]]/product/search" method="get">
            <div class="off_canvas_menu_item">
                <label for="search_keyword">Search for Products</label>
            </div>
            <div class="off_canvas_menu_item">
                <input id="search_keyword" name="search_keyword" type="text" placeholder="Search by Product Name, Code">
            </div>
            <div class="off_canvas_menu_item">
                <input type="submit" value="Search">
            </div>
        </form>
    </div><!-- .off_canvas_menu_section_main_menu -->
</div><!-- .off_canvas_menu_right -->