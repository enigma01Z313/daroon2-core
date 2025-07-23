window.daroon2_update_therapists_count = function (count) {
  $("#available-therapists span").text(count);

  const section1 = `<section class="middle-call-to-action only-lg pl-8 pr-8 mt-1 mb-1">
                        <div class="d-flex direction-column align-items-start pl-8 pr-8 mt-8 mb-8"><h2 class="title3 w-100 mb-5">Not Sure Who to Choose?</h2>
                    <p class="title3 w-100 color-content-secondary mb-7">
                        Start with a <span class="color-action-ember">FREE</span>. Pay upfront, but it’s on us when you book your first therapy.
                    </p>
                    <a class="btn btn-primary btn-size-l btn-style-black" href="http://localhost/daroon/team/saeed-heydarian-2/">
                        <span>Start Now</span>
                        <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M25.4468 13.3801L18.3651 6.29843C18.0268 5.96009 17.4668 5.96009 17.1285 6.29843C16.7901 6.63676 16.7901 7.19676 17.1285 7.53509L22.7168 13.1234H4.99512C4.51678 13.1234 4.12012 13.5201 4.12012 13.9984C4.12012 14.4768 4.51678 14.8734 4.99512 14.8734H22.7168L17.1285 20.4618C16.7901 20.8001 16.7901 21.3601 17.1285 21.6984C17.3035 21.8734 17.5251 21.9551 17.7468 21.9551C17.9685 21.9551 18.1901 21.8734 18.3651 21.6984L25.4468 14.6168C25.7851 14.2784 25.7851 13.7184 25.4468 13.3801Z"></path>
                        </svg>
                    </a></div>
                    </section>`;
  const section2 = `<section class="middle-call-to-action only-md mt-1 mb-1">
                        <div class="d-flex direction-column align-items-start mt-8 mb-8"><h2 class="title3 w-100 mb-5">Not Sure Who to Choose?</h2>
                    <p class="title3 w-100 color-content-secondary mb-7">
                        Start with a <span class="color-action-ember">FREE</span>. Pay upfront, but it’s on us when you book your first therapy.
                    </p>
                    <a class="btn btn-primary btn-size-l btn-style-black" href="http://localhost/daroon/team/saeed-heydarian-2/">
                        <span>Start Now</span>
                        <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M25.4468 13.3801L18.3651 6.29843C18.0268 5.96009 17.4668 5.96009 17.1285 6.29843C16.7901 6.63676 16.7901 7.19676 17.1285 7.53509L22.7168 13.1234H4.99512C4.51678 13.1234 4.12012 13.5201 4.12012 13.9984C4.12012 14.4768 4.51678 14.8734 4.99512 14.8734H22.7168L17.1285 20.4618C16.7901 20.8001 16.7901 21.3601 17.1285 21.6984C17.3035 21.8734 17.5251 21.9551 17.7468 21.9551C17.9685 21.9551 18.1901 21.8734 18.3651 21.6984L25.4468 14.6168C25.7851 14.2784 25.7851 13.7184 25.4468 13.3801Z"></path>
                        </svg>
                    </a></div>
                    </section>`;
  const section3 = `<section class="middle-call-to-action only-sm mt-1 mb-1">
                        <div class="d-flex direction-column align-items-start mt-8 mb-8"><h2 class="title3 w-100 mb-5">Not Sure Who to Choose?</h2>
                    <p class="title3 w-100 color-content-secondary mb-7">
                        Start with a <span class="color-action-ember">FREE</span>. Pay upfront, but it’s on us when you book your first therapy.
                    </p>
                    <a class="btn btn-primary btn-size-l btn-style-black" href="http://localhost/daroon/team/saeed-heydarian-2/">
                        <span>Start Now</span>
                        <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M25.4468 13.3801L18.3651 6.29843C18.0268 5.96009 17.4668 5.96009 17.1285 6.29843C16.7901 6.63676 16.7901 7.19676 17.1285 7.53509L22.7168 13.1234H4.99512C4.51678 13.1234 4.12012 13.5201 4.12012 13.9984C4.12012 14.4768 4.51678 14.8734 4.99512 14.8734H22.7168L17.1285 20.4618C16.7901 20.8001 16.7901 21.3601 17.1285 21.6984C17.3035 21.8734 17.5251 21.9551 17.7468 21.9551C17.9685 21.9551 18.1901 21.8734 18.3651 21.6984L25.4468 14.6168C25.7851 14.2784 25.7851 13.7184 25.4468 13.3801Z"></path>
                        </svg>
                    </a></div>
                    </section>`;
  const section4 = `<section class="middle-call-to-action only-xs">
                        <div class="d-flex direction-column align-items-center text-center mt-8 mb-8"><h2 class="title3 w-100 mb-5">Not Sure Who to Choose?</h2>
                    <p class="title3 w-100 color-content-secondary mb-7">
                        Start with a <span class="color-action-ember">FREE</span>. Pay upfront, but it’s on us when you book your first therapy.
                    </p>
                    <a class="btn btn-primary btn-size-l btn-style-black" href="http://localhost/daroon/team/saeed-heydarian-2/">
                        <span>Start Now</span>
                        <svg width="29" height="28" viewBox="0 0 29 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M25.4468 13.3801L18.3651 6.29843C18.0268 5.96009 17.4668 5.96009 17.1285 6.29843C16.7901 6.63676 16.7901 7.19676 17.1285 7.53509L22.7168 13.1234H4.99512C4.51678 13.1234 4.12012 13.5201 4.12012 13.9984C4.12012 14.4768 4.51678 14.8734 4.99512 14.8734H22.7168L17.1285 20.4618C16.7901 20.8001 16.7901 21.3601 17.1285 21.6984C17.3035 21.8734 17.5251 21.9551 17.7468 21.9551C17.9685 21.9551 18.1901 21.8734 18.3651 21.6984L25.4468 14.6168C25.7851 14.2784 25.7851 13.7184 25.4468 13.3801Z"></path>
                        </svg>
                    </a></div>
                    </section>`;

  $(".middle-call-to-action").remove();

  if (count > 25) {
    let articleCount = 0;
    $('.daroon2-therapist-list article:visible').each(function() {
      articleCount++;
      if (articleCount === 20) {
        $(this).after(section1);
      }
    });
  }
  
  if (count > 18) {
    let articleCount = 0;
    $('.daroon2-therapist-list article:visible').each(function() {
      articleCount++;
      if (articleCount === 15) {
        $(this).after(section2);
      }
    });
  }

  if (count > 12) {
    let articleCount = 0;
    $('.daroon2-therapist-list article:visible').each(function() {
      articleCount++;
      if (articleCount === 10) {
        $(this).after(section3);
      }
    });
  }

  if (count > 6) {
    let articleCount = 0;
    $('.daroon2-therapist-list article:visible').each(function() {
      articleCount++;
      if (articleCount === 5) {
        $(this).after(section4);
      }
    });
  }

};

jQuery(document).ready(function ($) {
  const $search = $("#daroon2-therapist-search");
  const $cats = $(".daroon2-therapist-categories .chip[data-slug]");
  const $items = $(".daroon2-therapist-item");

  $search.on("input", function () {
    filterTherapists();
  });

  $cats.on("click", function () {
    $cats.removeClass("active");
    $(this).addClass("active");
    filterTherapists();
  });

  $(".search-trigger.chip").click(function () {
    $(".daroon2-therapist-categories-wrap").fadeOut(450, function () {
      $(".therapist-list-search-wrap-wrap").fadeIn(450);
      $("#daroon2-therapist-search").focus();
    });
  });

  $(".search-close.chip").click(function () {
    $("#daroon2-therapist-search").val("").trigger("input");
    $(".therapist-list-search-wrap-wrap").fadeOut(450, function () {
      $(".daroon2-therapist-categories-wrap").fadeIn(450);
    });
  });

  function filterTherapists() {
    const searchVal = $search.val().toLowerCase();
    const activeCat = $(".daroon2-therapist-categories .chip.active").data("slug");
    let visibleCount = 0;

    $items.each(function () {
      const $item = $(this);
      const name = $item.find(".daroon2-therapist-name").text().toLowerCase();
      const cats = $item.data("cats") ? $item.data("cats").split(" ") : [];
      let show = true;
      if (searchVal && !name.includes(searchVal)) show = false;
      if (activeCat && activeCat !== "all" && cats.indexOf(activeCat) === -1) show = false;
      $item.toggle(show);
      if (show) visibleCount++;
    });

    window.daroon2_update_therapists_count(visibleCount);
  }
});
