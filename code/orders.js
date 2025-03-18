const prodavaneDiv = document.getElementById('prodavene_div');
const kupovaneDiv = document.getElementById('kupovane_div');
const prodavaneButton = document.getElementById('prodavene_button');
const kupovaneButton = document.getElementById('kupovane_button');

showProdavane()

function showProdavane() {
    prodavaneDiv.classList.remove('hidden');
    kupovaneDiv.classList.add('hidden');
    prodavaneButton.classList.remove('hover:bg-blue-600', 'bg-blue-500')
    prodavaneButton.classList.add('cursor-not-allowed', 'bg-gray-400')
    kupovaneButton.classList.remove('cursor-not-allowed', 'bg-gray-400')
    kupovaneButton.classList.add('hover:bg-blue-600', 'bg-blue-500')
}

function showKupovane() {
    kupovaneDiv.classList.remove('hidden');
    prodavaneDiv.classList.add('hidden');
    kupovaneButton.classList.remove('hover:bg-blue-600', 'bg-blue-500')
    kupovaneButton.classList.add('cursor-not-allowed', 'bg-gray-400')
    prodavaneButton.classList.remove('cursor-not-allowed', 'bg-gray-400')
    prodavaneButton.classList.add('hover:bg-blue-600', 'bg-blue-500')
}