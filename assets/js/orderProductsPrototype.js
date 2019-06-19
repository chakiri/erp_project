var $collectionHolder;

// setup an "add a tag" link
var $addProductButton = $('<a href="#" class="add_product_link float-right">Add a product</a>');
var $newLinkLi = $('<div class=""></div>').append($addProductButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('.products');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    //add new empty product form
    addProductForm($collectionHolder, $newLinkLi);

    $addProductButton.on('click', function(e) {
        e.preventDefault();
        // add a new tag form (see next code block)
        addProductForm($collectionHolder, $newLinkLi);
    });
});

function addProductForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<div></div>').append(newForm);
    $newLinkLi.before($newFormLi);

    // delete link to the new form
    deleteProductForm($newFormLi);
}

// ------------ Delete Product ----------------

jQuery(document).ready(function () {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('.products');

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('.product-form').each(function() {
        deleteProductForm($(this));
    });
});

function deleteProductForm($productFormLi) {

    var $removeFormLink = $productFormLi.find('.js-remove-form-product');

    $removeFormLink.on('click', function (e){
        e.preventDefault();
        // remove the li for the product form
        $productFormLi.remove();
    });
}

// --------- Disable removeFormLink to the first row ----------
jQuery(document).ready(function () {
    $collectionHolder = $('.products');

    $collectionHolder.find('.product-form:first a').remove();
});
