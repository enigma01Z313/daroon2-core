<?php

function daroon2_dashboard_account_func()
{
  $user_info = daroon2_get_customer_info();

  // d($user_info);
?>
  <style>
    #dashboard-page>section .daroon2-dashboard-content {
      background: var(--dashboard-color-surface-light4);
    }
  </style>
  <div class="daroon2-dashboard-profile" style="width: 100%">
    <div id="profile-form">
      <?php
        $has_image = false;
        $photo_url = get_template_directory_uri() . '/assets/icons/Avatar.svg';
        if ($user_info['user-profile-image']['value'] != '') {
          $has_image = true;
          $photo_url = $user_info['user-profile-image']['value'];
        }
      ?>
      <div 
        class="profile-image d-flex align-items-center justify-content-start <?= $has_image ? 'has-image' : ''; ?>">
        <div class="the-image">
          <img src="<?= $photo_url; ?>" alt="Profile image" />
        </div>
        <div class="d-flex">
          <input type="file" name="profile-image" id="profile-image" class="d-none" />
          <label for="profile-image" class="btn btn-style-brand btn-size-l">
            <span>Upload an image</span>
          </label>

          <label for="profile-image" class="btn btn-style-white btn-size-l">
            <span>Upload new image</span>
          </label>

          <button for="profile-image" class="btn btn-style-subtle btn-size-l">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M21.4902 5.30325C19.8802 5.14325 18.2702 5.02325 16.6602 4.93325L16.4402 3.63325C16.2902 2.71325 16.0702 1.33325 13.7302 1.33325H11.1102C8.7802 1.33325 8.5602 2.66325 8.4002 3.62325L8.1802 4.90325C7.2402 4.95325 6.3102 5.01325 5.3802 5.11325L3.3402 5.31325C2.9302 5.35325 2.6302 5.71325 2.6702 6.13325C2.7102 6.55325 3.0802 6.85325 3.5002 6.81325L5.5402 6.61325C10.7702 6.08325 16.0502 6.28325 21.3402 6.81325H21.4202C21.8002 6.81325 22.1302 6.52325 22.1602 6.13325C22.2002 5.72325 21.9002 5.35325 21.4902 5.31325V5.30325ZM9.7202 4.82325L9.8902 3.86325C10.0402 2.97325 10.0602 2.83325 11.1202 2.83325H13.7402C14.8002 2.83325 14.8302 3.00325 14.9702 3.87325L15.1402 4.85325C13.3302 4.78325 11.5202 4.76325 9.7202 4.82325Z" fill="black" />
              <path d="M19.3202 8.46324C18.9102 8.44324 18.5502 8.75324 18.5202 9.16324L17.8702 19.2332C17.7702 20.7532 17.7302 21.3232 15.6302 21.3232H9.21019C7.12019 21.3232 7.08019 20.7532 6.97019 19.2332L6.32019 9.16324C6.29019 8.75324 5.94019 8.44324 5.52019 8.46324C5.11019 8.49324 4.79019 8.85324 4.82019 9.26324L5.47019 19.3332C5.58019 20.8932 5.72019 22.8232 9.21019 22.8232H15.6302C19.1202 22.8232 19.2602 20.8932 19.3702 19.3332L20.0202 9.26324C20.0502 8.84324 19.7302 8.49324 19.3202 8.46324Z" fill="black" />
              <path d="M10.7502 15.8232C10.3402 15.8232 10.0002 16.1632 10.0002 16.5732C10.0002 16.9832 10.3402 17.3232 10.7502 17.3232H14.0802C14.4902 17.3232 14.8302 16.9832 14.8302 16.5732C14.8302 16.1632 14.4902 15.8232 14.0802 15.8232H10.7502Z" fill="black" />
              <path d="M15.6702 12.5732C15.6702 12.1632 15.3302 11.8232 14.9202 11.8232H9.92021C9.51021 11.8232 9.17021 12.1632 9.17021 12.5732C9.17021 12.9832 9.51021 13.3232 9.92021 13.3232H14.9202C15.3302 13.3232 15.6702 12.9832 15.6702 12.5732Z" fill="black" />
            </svg>
          </button>
        </div>
      </div>

      <h2 class="title1 mb-2 mt-4">Personal info</h2>

      <div class="field-group">

        <div class="field-item <?= $user_info['user-full-name']['value'] != '' ? 'has-value' : ''; ?>">
          <div class="trigger d-flex justify-content-between align-items-center">
            <div class="field-data">
              <h3 class="title2">Name</h3>
              <p class="title2"><?= $user_info['user-full-name']['value']; ?></p>
            </div>
            <div class="actions-wrapper">
              <a class="btn btn-style-outline btn-size-l add-field-value">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M17.926 11.2593H12.7408V6.07411C12.7408 5.66918 12.405 5.33337 12 5.33337C11.5951 5.33337 11.2593 5.66918 11.2593 6.07411V11.2593H6.07411C5.66918 11.2593 5.33337 11.5951 5.33337 12C5.33337 12.405 5.66918 12.7408 6.07411 12.7408H11.2593V17.926C11.2593 18.3309 11.5951 18.6667 12 18.6667C12.405 18.6667 12.7408 18.3309 12.7408 17.926V12.7408H17.926C18.3309 12.7408 18.6667 12.405 18.6667 12C18.6667 11.5951 18.3309 11.2593 17.926 11.2593Z"
                    fill="black" />
                </svg>
                <span>Add</span>
              </a>
              <a class="btn btn-style-plain btn-size-l edit-field-value">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5.54 19.4319C5.7 19.4319 5.85 19.4119 6.02 19.4019L9.24 18.8519C9.85 18.7519 10.63 18.3319 11.05 17.8819L19.26 9.19192C21.31 7.02191 21.25 4.88191 19.08 2.83191C16.91 0.781914 14.77 0.841914 12.72 3.01191L4.51 11.7019C4.08 12.1419 3.71 12.9519 3.64 13.5619L3.27 16.8019C3.18 17.6019 3.43 18.3419 3.95 18.8319C4.36 19.2219 4.93 19.4319 5.54 19.4319ZM15.93 2.82191C16.55 2.82191 17.24 3.14191 18.04 3.91191C19.85 5.61192 19.4 6.83191 18.16 8.15192L17.07 9.30192C14.91 8.94192 13.2 7.30192 12.73 5.16191L13.81 4.01191C14.49 3.29191 15.16 2.81191 15.93 2.81191V2.82191ZM5.13 13.7219C5.17 13.4319 5.4 12.9319 5.6 12.7219L11.53 6.44191C12.27 8.41191 13.87 9.93192 15.88 10.5619L9.95 16.8419C9.75 17.0519 9.27 17.3119 8.98 17.3619L5.76 17.9119C5.43 17.9619 5.16 17.9019 4.98 17.7319C4.8 17.5619 4.72 17.2919 4.76 16.9619L5.13 13.7219Z" fill="black" />
                  <path d="M21 21.1619H3C2.59 21.1619 2.25 21.5019 2.25 21.9119C2.25 22.3219 2.59 22.6619 3 22.6619H21C21.41 22.6619 21.75 22.3219 21.75 21.9119C21.75 21.5019 21.41 21.1619 21 21.1619Z" fill="black" />
                </svg>
              </a>
            </div>
          </div>

          <div class="opened-field">
            <article class="content p-4">
              <div class="header d-flex direction-column align-items-start">
                <div class="field-data">
                  <h2 class="title3">Name</h2>
                </div>
                <p class="body1 color-content-secondary">Please enter your full name.</p>
              </div>
              <div>
                <input
                  name="user-full-name"
                  class="input w-100 body1"
                  type="text"
                  placeholder="Jon Doe"
                  value="<?= $user_info['user-full-name']['value']; ?>" />
              </div>
              <div class="footer d-flex justify-content-end">
                <button class="btn btn-style-outline btn-size-l discard-field-value mr-2">
                  <span>Cancel</span>
                </button>
                <button class="btn btn-style-brand btn-size-l apply-field-valud">
                  <span>Apply</span>
                </button>
              </div>
            </article>
            <div class="overlay"></div>
          </div>
        </div>

        <div class="field-item <?= $user_info['user-email']['value'] != '' ? 'has-value' : ''; ?>">
          <div class="trigger d-flex justify-content-between align-items-center">
            <div class="field-data">
              <h3 class="title2">Email</h3>
              <p class="title2"><?= $user_info['user-email']['value']; ?></p>
            </div>
            <div class="actions-wrapper">
              <a class="btn btn-style-outline btn-size-l add-field-value">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M17.926 11.2593H12.7408V6.07411C12.7408 5.66918 12.405 5.33337 12 5.33337C11.5951 5.33337 11.2593 5.66918 11.2593 6.07411V11.2593H6.07411C5.66918 11.2593 5.33337 11.5951 5.33337 12C5.33337 12.405 5.66918 12.7408 6.07411 12.7408H11.2593V17.926C11.2593 18.3309 11.5951 18.6667 12 18.6667C12.405 18.6667 12.7408 18.3309 12.7408 17.926V12.7408H17.926C18.3309 12.7408 18.6667 12.405 18.6667 12C18.6667 11.5951 18.3309 11.2593 17.926 11.2593Z"
                    fill="black" />
                </svg>
                <span>Add</span>
              </a>
              <a class="btn btn-style-plain btn-size-l edit-field-value">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5.54 19.4319C5.7 19.4319 5.85 19.4119 6.02 19.4019L9.24 18.8519C9.85 18.7519 10.63 18.3319 11.05 17.8819L19.26 9.19192C21.31 7.02191 21.25 4.88191 19.08 2.83191C16.91 0.781914 14.77 0.841914 12.72 3.01191L4.51 11.7019C4.08 12.1419 3.71 12.9519 3.64 13.5619L3.27 16.8019C3.18 17.6019 3.43 18.3419 3.95 18.8319C4.36 19.2219 4.93 19.4319 5.54 19.4319ZM15.93 2.82191C16.55 2.82191 17.24 3.14191 18.04 3.91191C19.85 5.61192 19.4 6.83191 18.16 8.15192L17.07 9.30192C14.91 8.94192 13.2 7.30192 12.73 5.16191L13.81 4.01191C14.49 3.29191 15.16 2.81191 15.93 2.81191V2.82191ZM5.13 13.7219C5.17 13.4319 5.4 12.9319 5.6 12.7219L11.53 6.44191C12.27 8.41191 13.87 9.93192 15.88 10.5619L9.95 16.8419C9.75 17.0519 9.27 17.3119 8.98 17.3619L5.76 17.9119C5.43 17.9619 5.16 17.9019 4.98 17.7319C4.8 17.5619 4.72 17.2919 4.76 16.9619L5.13 13.7219Z" fill="black" />
                  <path d="M21 21.1619H3C2.59 21.1619 2.25 21.5019 2.25 21.9119C2.25 22.3219 2.59 22.6619 3 22.6619H21C21.41 22.6619 21.75 22.3219 21.75 21.9119C21.75 21.5019 21.41 21.1619 21 21.1619Z" fill="black" />
                </svg>
              </a>
            </div>
          </div>

          <div class="opened-field">
            <article class="content p-4">
              <div class="header d-flex direction-column align-items-start">
                <h2 class="title3">Email</h2>
                <p class="body1 color-content-secondary">Please enter your main email you'll use to login.</p>
              </div>
              <div>
                <input
                  name="user-email"
                  class="input w-100 body1"
                  type="text"
                  placeholder="example@domain.com"
                  value="<?= $user_info['user-email']['value']; ?>" />
              </div>
              <div class="footer d-flex justify-content-end">
                <button class="btn btn-style-outline btn-size-l discard-field-value mr-2">
                  <span>Cancel</span>
                </button>
                <button class="btn btn-style-brand btn-size-l apply-field-valud">
                  <span>Apply</span>
                </button>
              </div>
            </article>
            <div class="overlay"></div>
          </div>
        </div>

        <div class="field-item <?= $user_info['user-phone']['value'] != '' ? 'has-value' : ''; ?>">
          <div class="trigger d-flex justify-content-between align-items-center">
            <div class="field-data">
              <h3 class="title2">Phone</h3>
              <p class="title2"><?= $user_info['user-phone']['value']; ?></p>
            </div>
            <div class="actions-wrapper">
              <a class="btn btn-style-outline btn-size-l add-field-value">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M17.926 11.2593H12.7408V6.07411C12.7408 5.66918 12.405 5.33337 12 5.33337C11.5951 5.33337 11.2593 5.66918 11.2593 6.07411V11.2593H6.07411C5.66918 11.2593 5.33337 11.5951 5.33337 12C5.33337 12.405 5.66918 12.7408 6.07411 12.7408H11.2593V17.926C11.2593 18.3309 11.5951 18.6667 12 18.6667C12.405 18.6667 12.7408 18.3309 12.7408 17.926V12.7408H17.926C18.3309 12.7408 18.6667 12.405 18.6667 12C18.6667 11.5951 18.3309 11.2593 17.926 11.2593Z"
                    fill="black" />
                </svg>
                <span>Add</span>
              </a>
              <a class="btn btn-style-plain btn-size-l edit-field-value">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5.54 19.4319C5.7 19.4319 5.85 19.4119 6.02 19.4019L9.24 18.8519C9.85 18.7519 10.63 18.3319 11.05 17.8819L19.26 9.19192C21.31 7.02191 21.25 4.88191 19.08 2.83191C16.91 0.781914 14.77 0.841914 12.72 3.01191L4.51 11.7019C4.08 12.1419 3.71 12.9519 3.64 13.5619L3.27 16.8019C3.18 17.6019 3.43 18.3419 3.95 18.8319C4.36 19.2219 4.93 19.4319 5.54 19.4319ZM15.93 2.82191C16.55 2.82191 17.24 3.14191 18.04 3.91191C19.85 5.61192 19.4 6.83191 18.16 8.15192L17.07 9.30192C14.91 8.94192 13.2 7.30192 12.73 5.16191L13.81 4.01191C14.49 3.29191 15.16 2.81191 15.93 2.81191V2.82191ZM5.13 13.7219C5.17 13.4319 5.4 12.9319 5.6 12.7219L11.53 6.44191C12.27 8.41191 13.87 9.93192 15.88 10.5619L9.95 16.8419C9.75 17.0519 9.27 17.3119 8.98 17.3619L5.76 17.9119C5.43 17.9619 5.16 17.9019 4.98 17.7319C4.8 17.5619 4.72 17.2919 4.76 16.9619L5.13 13.7219Z" fill="black" />
                  <path d="M21 21.1619H3C2.59 21.1619 2.25 21.5019 2.25 21.9119C2.25 22.3219 2.59 22.6619 3 22.6619H21C21.41 22.6619 21.75 22.3219 21.75 21.9119C21.75 21.5019 21.41 21.1619 21 21.1619Z" fill="black" />
                </svg>
              </a>
            </div>
          </div>

          <div class="opened-field">
            <article class="content p-4">
              <div class="header d-flex direction-column align-items-start">
                <h2 class="title3">Phone</h2>
                <p class="body1 color-content-secondary">Please enter your phone number.</p>
              </div>
              <div>
                <input
                  name="user-phone"
                  class="input w-100 body1"
                  type="text"
                  placeholder="+98990000000"
                  value="<?= $user_info['user-phone']['value']; ?>" />
              </div>
              <div class="footer d-flex justify-content-end">
                <button class="btn btn-style-outline btn-size-l discard-field-value mr-2">
                  <span>Cancel</span>
                </button>
                <button class="btn btn-style-brand btn-size-l apply-field-valud">
                  <span>Apply</span>
                </button>
              </div>
            </article>
            <div class="overlay"></div>
          </div>
        </div>

        <div class="field-item <?= $user_info['user-date-of-birth']['value'] != '' ? 'has-value' : ''; ?>">
          <div class="trigger d-flex justify-content-between align-items-center">
            <div class="field-data">
              <h3 class="title2">Date of birth</h3>
              <p class="title2"><?= $user_info['user-date-of-birth']['value']; ?></p>
            </div>
            <div class="actions-wrapper">
              <a class="btn btn-style-outline btn-size-l add-field-value">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M17.926 11.2593H12.7408V6.07411C12.7408 5.66918 12.405 5.33337 12 5.33337C11.5951 5.33337 11.2593 5.66918 11.2593 6.07411V11.2593H6.07411C5.66918 11.2593 5.33337 11.5951 5.33337 12C5.33337 12.405 5.66918 12.7408 6.07411 12.7408H11.2593V17.926C11.2593 18.3309 11.5951 18.6667 12 18.6667C12.405 18.6667 12.7408 18.3309 12.7408 17.926V12.7408H17.926C18.3309 12.7408 18.6667 12.405 18.6667 12C18.6667 11.5951 18.3309 11.2593 17.926 11.2593Z"
                    fill="black" />
                </svg>
                <span>Add</span>
              </a>
              <a class="btn btn-style-plain btn-size-l edit-field-value">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5.54 19.4319C5.7 19.4319 5.85 19.4119 6.02 19.4019L9.24 18.8519C9.85 18.7519 10.63 18.3319 11.05 17.8819L19.26 9.19192C21.31 7.02191 21.25 4.88191 19.08 2.83191C16.91 0.781914 14.77 0.841914 12.72 3.01191L4.51 11.7019C4.08 12.1419 3.71 12.9519 3.64 13.5619L3.27 16.8019C3.18 17.6019 3.43 18.3419 3.95 18.8319C4.36 19.2219 4.93 19.4319 5.54 19.4319ZM15.93 2.82191C16.55 2.82191 17.24 3.14191 18.04 3.91191C19.85 5.61192 19.4 6.83191 18.16 8.15192L17.07 9.30192C14.91 8.94192 13.2 7.30192 12.73 5.16191L13.81 4.01191C14.49 3.29191 15.16 2.81191 15.93 2.81191V2.82191ZM5.13 13.7219C5.17 13.4319 5.4 12.9319 5.6 12.7219L11.53 6.44191C12.27 8.41191 13.87 9.93192 15.88 10.5619L9.95 16.8419C9.75 17.0519 9.27 17.3119 8.98 17.3619L5.76 17.9119C5.43 17.9619 5.16 17.9019 4.98 17.7319C4.8 17.5619 4.72 17.2919 4.76 16.9619L5.13 13.7219Z" fill="black" />
                  <path d="M21 21.1619H3C2.59 21.1619 2.25 21.5019 2.25 21.9119C2.25 22.3219 2.59 22.6619 3 22.6619H21C21.41 22.6619 21.75 22.3219 21.75 21.9119C21.75 21.5019 21.41 21.1619 21 21.1619Z" fill="black" />
                </svg>
              </a>
            </div>
          </div>

          <div class="opened-field">
            <article class="content p-4">
              <div class="header d-flex direction-column align-items-start">
                <h2 class="title3">Date of birth</h2>
                <p class="body1 color-content-secondary">Your date of birth is used to calculate your age.</p>
              </div>
              <div>
                <input
                  name="user-date-of-birth"
                  class="input w-100 body1"
                  type="text"
                  placeholder="YYYY-MM-DD"
                  value="<?= $user_info['user-date-of-birth']['value']; ?>" />
              </div>
              <div class="footer d-flex justify-content-end">
                <button class="btn btn-style-outline btn-size-l discard-field-value mr-2">
                  <span>Cancel</span>
                </button>
                <button class="btn btn-style-brand btn-size-l apply-field-valud">
                  <span>Apply</span>
                </button>
              </div>
            </article>
            <div class="overlay"></div>
          </div>
        </div>

        <div class="field-item <?= $user_info['user-gender']['value'] != '' ? 'has-value' : ''; ?>">
          <div class="trigger d-flex justify-content-between align-items-center">
            <div class="field-data">
              <h3 class="title2">Gender</h3>
              <p class="title2"><?= $user_info['user-gender']['value']; ?></p>
            </div>
            <div class="actions-wrapper">
              <a class="btn btn-style-outline btn-size-l add-field-value">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M17.926 11.2593H12.7408V6.07411C12.7408 5.66918 12.405 5.33337 12 5.33337C11.5951 5.33337 11.2593 5.66918 11.2593 6.07411V11.2593H6.07411C5.66918 11.2593 5.33337 11.5951 5.33337 12C5.33337 12.405 5.66918 12.7408 6.07411 12.7408H11.2593V17.926C11.2593 18.3309 11.5951 18.6667 12 18.6667C12.405 18.6667 12.7408 18.3309 12.7408 17.926V12.7408H17.926C18.3309 12.7408 18.6667 12.405 18.6667 12C18.6667 11.5951 18.3309 11.2593 17.926 11.2593Z"
                    fill="black" />
                </svg>
                <span>Add</span>
              </a>
              <a class="btn btn-style-plain btn-size-l edit-field-value">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5.54 19.4319C5.7 19.4319 5.85 19.4119 6.02 19.4019L9.24 18.8519C9.85 18.7519 10.63 18.3319 11.05 17.8819L19.26 9.19192C21.31 7.02191 21.25 4.88191 19.08 2.83191C16.91 0.781914 14.77 0.841914 12.72 3.01191L4.51 11.7019C4.08 12.1419 3.71 12.9519 3.64 13.5619L3.27 16.8019C3.18 17.6019 3.43 18.3419 3.95 18.8319C4.36 19.2219 4.93 19.4319 5.54 19.4319ZM15.93 2.82191C16.55 2.82191 17.24 3.14191 18.04 3.91191C19.85 5.61192 19.4 6.83191 18.16 8.15192L17.07 9.30192C14.91 8.94192 13.2 7.30192 12.73 5.16191L13.81 4.01191C14.49 3.29191 15.16 2.81191 15.93 2.81191V2.82191ZM5.13 13.7219C5.17 13.4319 5.4 12.9319 5.6 12.7219L11.53 6.44191C12.27 8.41191 13.87 9.93192 15.88 10.5619L9.95 16.8419C9.75 17.0519 9.27 17.3119 8.98 17.3619L5.76 17.9119C5.43 17.9619 5.16 17.9019 4.98 17.7319C4.8 17.5619 4.72 17.2919 4.76 16.9619L5.13 13.7219Z" fill="black" />
                  <path d="M21 21.1619H3C2.59 21.1619 2.25 21.5019 2.25 21.9119C2.25 22.3219 2.59 22.6619 3 22.6619H21C21.41 22.6619 21.75 22.3219 21.75 21.9119C21.75 21.5019 21.41 21.1619 21 21.1619Z" fill="black" />
                </svg>
              </a>
            </div>
          </div>

          <div class="opened-field">
            <article class="content p-4">
              <div class="header d-flex direction-column align-items-start">
                <h2 class="title3">Gender</h2>
                <p class="body1 color-content-secondary">Please select your gender.</p>
              </div>
              <div>
                <select name="user-gender" class="input w-100 body1">
                  <option value="Male" <?= $user_info['user-gender']['value'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                  <option value="Female" <?= $user_info['user-gender']['value'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                  <option value="Other" <?= $user_info['user-gender']['value'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
              </div>
              <div class="footer d-flex justify-content-end">
                <button class="btn btn-style-outline btn-size-l discard-field-value mr-2">
                  <span>Cancel</span>
                </button>
                <button class="btn btn-style-brand btn-size-l apply-field-valud">
                  <span>Apply</span>
                </button>
              </div>
            </article>
            <div class="overlay"></div>
          </div>
        </div>

      </div>

      <h2 class="title1 mb-2 mt-4">Emergency contact</h2>

      <div class="field-group">

        <div class="field-item <?= $user_info['user-emergency-name']['value'] != '' ? 'has-value' : ''; ?>">
          <div class="trigger d-flex justify-content-between align-items-center">
            <div class="field-data">
              <h3 class="title2">Name</h3>
              <p class="title2"><?= $user_info['user-emergency-name']['value']; ?></p>
            </div>
            <div class="actions-wrapper">
              <a class="btn btn-style-outline btn-size-l add-field-value">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M17.926 11.2593H12.7408V6.07411C12.7408 5.66918 12.405 5.33337 12 5.33337C11.5951 5.33337 11.2593 5.66918 11.2593 6.07411V11.2593H6.07411C5.66918 11.2593 5.33337 11.5951 5.33337 12C5.33337 12.405 5.66918 12.7408 6.07411 12.7408H11.2593V17.926C11.2593 18.3309 11.5951 18.6667 12 18.6667C12.405 18.6667 12.7408 18.3309 12.7408 17.926V12.7408H17.926C18.3309 12.7408 18.6667 12.405 18.6667 12C18.6667 11.5951 18.3309 11.2593 17.926 11.2593Z"
                    fill="black" />
                </svg>
                <span>Add</span>
              </a>
              <a class="btn btn-style-plain btn-size-l edit-field-value">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5.54 19.4319C5.7 19.4319 5.85 19.4119 6.02 19.4019L9.24 18.8519C9.85 18.7519 10.63 18.3319 11.05 17.8819L19.26 9.19192C21.31 7.02191 21.25 4.88191 19.08 2.83191C16.91 0.781914 14.77 0.841914 12.72 3.01191L4.51 11.7019C4.08 12.1419 3.71 12.9519 3.64 13.5619L3.27 16.8019C3.18 17.6019 3.43 18.3419 3.95 18.8319C4.36 19.2219 4.93 19.4319 5.54 19.4319ZM15.93 2.82191C16.55 2.82191 17.24 3.14191 18.04 3.91191C19.85 5.61192 19.4 6.83191 18.16 8.15192L17.07 9.30192C14.91 8.94192 13.2 7.30192 12.73 5.16191L13.81 4.01191C14.49 3.29191 15.16 2.81191 15.93 2.81191V2.82191ZM5.13 13.7219C5.17 13.4319 5.4 12.9319 5.6 12.7219L11.53 6.44191C12.27 8.41191 13.87 9.93192 15.88 10.5619L9.95 16.8419C9.75 17.0519 9.27 17.3119 8.98 17.3619L5.76 17.9119C5.43 17.9619 5.16 17.9019 4.98 17.7319C4.8 17.5619 4.72 17.2919 4.76 16.9619L5.13 13.7219Z" fill="black" />
                  <path d="M21 21.1619H3C2.59 21.1619 2.25 21.5019 2.25 21.9119C2.25 22.3219 2.59 22.6619 3 22.6619H21C21.41 22.6619 21.75 22.3219 21.75 21.9119C21.75 21.5019 21.41 21.1619 21 21.1619Z" fill="black" />
                </svg>
              </a>
            </div>
          </div>

          <div class="opened-field">
            <article class="content p-4">
              <div class="header d-flex direction-column align-items-start">
                <h2 class="title3">Name</h2>
                <p class="body1 color-content-secondary">Please enter your emergency contact's full name.</p>
              </div>
              <div>
                <input
                  name="user-emergency-name"
                  class="input w-100 body1"
                  type="text"
                  placeholder="John Doe"
                  value="<?= $user_info['user-emergency-name']['value']; ?>" />
              </div>
              <div class="footer d-flex justify-content-end">
                <button class="btn btn-style-outline btn-size-l discard-field-value mr-2">
                  <span>Cancel</span>
                </button>
                <button class="btn btn-style-brand btn-size-l apply-field-valud">
                  <span>Apply</span>
                </button>
              </div>
            </article>
            <div class="overlay"></div>
          </div>
        </div>

        <div class="field-item <?= $user_info['user-emergency-phone']['value'] != '' ? 'has-value' : ''; ?>">
          <div class="trigger d-flex justify-content-between align-items-center">
            <div class="field-data">
              <h3 class="title2">Phone number</h3>
              <p class="title2"><?= $user_info['user-emergency-phone']['value']; ?></p>
            </div>
            <div class="actions-wrapper">
              <a class="btn btn-style-outline btn-size-l add-field-value">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M17.926 11.2593H12.7408V6.07411C12.7408 5.66918 12.405 5.33337 12 5.33337C11.5951 5.33337 11.2593 5.66918 11.2593 6.07411V11.2593H6.07411C5.66918 11.2593 5.33337 11.5951 5.33337 12C5.33337 12.405 5.66918 12.7408 6.07411 12.7408H11.2593V17.926C11.2593 18.3309 11.5951 18.6667 12 18.6667C12.405 18.6667 12.7408 18.3309 12.7408 17.926V12.7408H17.926C18.3309 12.7408 18.6667 12.405 18.6667 12C18.6667 11.5951 18.3309 11.2593 17.926 11.2593Z"
                    fill="black" />
                </svg>
                <span>Add</span>
              </a>
              <a class="btn btn-style-plain btn-size-l edit-field-value">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M5.54 19.4319C5.7 19.4319 5.85 19.4119 6.02 19.4019L9.24 18.8519C9.85 18.7519 10.63 18.3319 11.05 17.8819L19.26 9.19192C21.31 7.02191 21.25 4.88191 19.08 2.83191C16.91 0.781914 14.77 0.841914 12.72 3.01191L4.51 11.7019C4.08 12.1419 3.71 12.9519 3.64 13.5619L3.27 16.8019C3.18 17.6019 3.43 18.3419 3.95 18.8319C4.36 19.2219 4.93 19.4319 5.54 19.4319ZM15.93 2.82191C16.55 2.82191 17.24 3.14191 18.04 3.91191C19.85 5.61192 19.4 6.83191 18.16 8.15192L17.07 9.30192C14.91 8.94192 13.2 7.30192 12.73 5.16191L13.81 4.01191C14.49 3.29191 15.16 2.81191 15.93 2.81191V2.82191ZM5.13 13.7219C5.17 13.4319 5.4 12.9319 5.6 12.7219L11.53 6.44191C12.27 8.41191 13.87 9.93192 15.88 10.5619L9.95 16.8419C9.75 17.0519 9.27 17.3119 8.98 17.3619L5.76 17.9119C5.43 17.9619 5.16 17.9019 4.98 17.7319C4.8 17.5619 4.72 17.2919 4.76 16.9619L5.13 13.7219Z" fill="black" />
                  <path d="M21 21.1619H3C2.59 21.1619 2.25 21.5019 2.25 21.9119C2.25 22.3219 2.59 22.6619 3 22.6619H21C21.41 22.6619 21.75 22.3219 21.75 21.9119C21.75 21.5019 21.41 21.1619 21 21.1619Z" fill="black" />
                </svg>
              </a>
            </div>
          </div>

          <div class="opened-field">
            <article class="content p-4">
              <div class="header d-flex direction-column align-items-start">
                <h2 class="title3">Phone number</h2>
                <p class="body1 color-content-secondary">Please enter your emergency contact's phone number.</p>
              </div>
              <div>
                <input
                  name="user-emergency-contact-phone"
                  class="input w-100 body1"
                  type="text"
                  placeholder="+98990000000"
                  value="<?= $user_info['user-emergency-phone']['value']; ?>" />
              </div>
              <div class="footer d-flex justify-content-end">
                <button class="btn btn-style-outline btn-size-l discard-field-value mr-2">
                  <span>Cancel</span>
                </button>
                <button class="btn btn-style-brand btn-size-l apply-field-valud">
                  <span>Apply</span>
                </button>
              </div>
            </article>
            <div class="overlay"></div>
          </div>
        </div>

      </div>
    </div>

    <section id="book-session-only-mobile" class="pt-3 pb-3 pl-5 pr-5">
      <a href="#" class="btn btn-size-m btn-style-brand">
        <span>Book a session</span>
      </a>
    </section>
  </div>
  <script>
    jQuery("#dashboard-profile a").addClass("active")
    //jQuery(document).ready(function() {
    //})

    const image_input = jQuery('#profile-image');
      image_input.on('change', (e) => {
        const file = e.target.files[0];
        
      })
  </script>
<?php
  return '';
}

add_shortcode('daroon2_dashboard_account', 'daroon2_dashboard_account_func');
