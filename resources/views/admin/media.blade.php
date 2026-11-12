<x-admin-layout :drk="true" title="Media">
   <x-admin.header />
   <x-admin.side />
   
   <div id="main-content" class="lg:ml-48 pt-16 p-6 transition-all duration-300 sm:ml-16 bg-[#f1f1f1] text-sm">
       <h2 class="text-xl font-bold mb-4">Media Library</h2>
       
       <button id="add-new" class="mb-4 bg-green-500 text-white px-4 py-2 rounded">Add New</button>
       
       <div id="media-container" class="grid grid-cols-2 md:grid-cols-4 gap-4"></div>
   </div>
   
   <div id="media-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center text-sm">
       <div class="bg-white p-6 rounded shadow-lg w-96">
           <h2 class="text-lg font-bold mb-4" id="modal-title">Upload Media</h2>
           <input type="file" id="media-input" class="border p-2 w-full mb-4" />
           <div class="md:flex md:justify-between md:gap-4">
                <button id="upload-btn" class="bg-blue-500 text-white px-4 py-2 rounded w-full relative">
                    <span id="upload-text">Upload</span>
                    <span id="upload-spinner" class="hidden absolute right-4 top-2"><i class="fas fa-spinner fa-spin"></i></span>
                </button>
                <button id="close-modal" class="bg-red-500 text-white px-4 py-2 rounded w-full">Close</button>
           </div>
       </div>
   </div>

   <script>
       let isEditing = false;
       let oldFileName = "";

       document.getElementById("add-new").addEventListener("click", function() {
           isEditing = false;
           oldFileName = "";
           document.getElementById("modal-title").innerText = "Upload Media";
           document.getElementById("media-modal").classList.remove("hidden");
       });

       document.getElementById("close-modal").addEventListener("click", function() {
           document.getElementById("media-modal").classList.add("hidden");
       });

       function toggleSpinner(show) {
           document.getElementById("upload-spinner").classList.toggle("hidden", !show);
           document.getElementById("upload-text").innerText = show ? "Processing..." : (isEditing ? "Edit" : "Upload");
           document.getElementById("upload-btn").disabled = show;
       }

       function formatFileSize(size) {
           if (size >= 1073741824) {
               return (size / 1073741824).toFixed(2) + " GB";
           } else if (size >= 1048576) {
               return (size / 1048576).toFixed(2) + " MB";
           } else {
               return (size / 1024).toFixed(2) + " KB";
           }
       }

       document.getElementById("upload-btn").addEventListener("click", function() {
           let file = document.getElementById("media-input").files[0];
           if (!file) return;

           let formData = new FormData();
           formData.append("media", file);
           if (isEditing) {
               formData.append("old_name", oldFileName); 
           }

           let url = isEditing ? "{{ route('admin.media.edit') }}" : "{{ route('admin.media.add') }}";
           
           toggleSpinner(true);
           
           fetch(url, {
               method: "POST",
               body: formData,
               headers: {
                   "X-CSRF-TOKEN": "{{ csrf_token() }}"
               }
           }).then(response => response.json()).then(() => {
               location.reload();
           }).finally(() => {
               toggleSpinner(false);
           });
       });

       fetch("{{ route('admin.media') }}")
       .then(response => response.json())
       .then(data => {
           let container = document.getElementById("media-container");
           container.innerHTML = "";

           data.forEach(media => {
               let div = document.createElement("div");
               div.classList.add("p-2", "border", "rounded", "bg-white");

               let img = document.createElement("img");
               img.src = media.url + "?t=" + new Date().getTime();
               img.classList.add("w-full", "h-32", "object-cover", "rounded");

               let meta = document.createElement("p");
               meta.innerText = `Name: ${media.name} | Size: ${formatFileSize(media.size)} | Type: ${media.type}`;
               meta.classList.add("text-xs", "text-gray-600", "mt-1");

               let btnCopy = document.createElement("button");
               btnCopy.innerText = "Copy Link";
               btnCopy.classList.add("mt-2", "bg-blue-500", "text-white", "px-2", "py-1", "rounded", "text-sm");
               btnCopy.onclick = () => navigator.clipboard.writeText(media.url);
               
               let btnEdit = document.createElement("button");
               btnEdit.innerText = "Edit";
               btnEdit.classList.add("mt-2", "bg-yellow-500", "text-white", "px-2", "py-1", "rounded", "text-sm", "ml-2");
               btnEdit.onclick = () => {
                   isEditing = true;
                   oldFileName = media.name;
                   document.getElementById("modal-title").innerText = "Edit Media";
                   document.getElementById("media-modal").classList.remove("hidden");
               };
               
               div.appendChild(img);
               div.appendChild(meta);
               div.appendChild(btnCopy);
               div.appendChild(btnEdit);
               container.appendChild(div);
           });
       });
   </script>
</x-admin-layout>
