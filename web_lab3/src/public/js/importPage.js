const form = document.getElementById('importForm');
const fileInput = document.getElementById('xml_file');
const button = document.getElementById('importBtn');
const status = document.getElementById('importStatus');
const statusText = document.getElementById('importStatusText');

const texts = {
  waiting: 'Waiting for a file to be uploaded',
  processing: 'Processing the file...',
  done: 'Import is finished'
};

function setState(state) {
  status.dataset.state = state;
  statusText.textContent = texts[state];
}

setState(status.dataset.state);

fileInput.addEventListener('change', () => {
  const file = fileInput.files[0];
  const valid = file && file.name.toLowerCase().endsWith('.xml');
  if (file && !valid) {
    fileInput.value = '';
    statusText.textContent = 'Only .xml files are allowed';
  } else if (!file) {
    setState('waiting');
  }
  button.disabled = !valid;
});

form.addEventListener('submit', () => {
  setState('processing');
  button.disabled = true;
  button.textContent = 'Importing...';
});