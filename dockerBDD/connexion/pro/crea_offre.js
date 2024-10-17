function showForm() {
            
    console.log("test");

    const selectedOption = document.querySelector('input[name="options"]:checked');
    console.log(selectedTag);
    const forms = document.querySelectorAll('.offer-form');

    
    forms.forEach(form => form.style.display = 'none');

    
    if (selectedOption) {
        const selectedForm = document.getElementById(selectedOption.value);
        if (selectedForm) {
            selectedForm.style.display = 'block';
        }
    }
}

function showTag() {
    const selectedTag = document.querySelector('#tag');
    const afterTag = document.querySelector('.offer-tag');

    afterTag.forEach(tag => tag.style.display = 'none');

    if (selectedTag) {
        const selectedFormTag = selectedTag.value;
        if (selectedFormTag) {
            document.getElementById('tag1').style.display = 'block';
        }
    }
}